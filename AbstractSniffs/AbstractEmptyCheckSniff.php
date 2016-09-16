<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AbstractSniffs;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class AbstractEmptyCheckSniff
 * Allows easily implement sniffs to detect wrong approach for checking empty variables in conditions.
 */
abstract class AbstractEmptyCheckSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage;

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode;

    /**
     * Name of function that should be found.
     *
     * @var string
     */
    protected $functionName;

    /**
     * List of comparison operators.
     *
     * @var array
     */
    protected $comparisonOperators = [
        T_GREATER_THAN,
        T_IS_NOT_IDENTICAL,
        T_IS_NOT_EQUAL
    ];

    /**
     * List of logic operators that show an end of condition.
     *
     * @var array
     */
    protected $logicOperators = [
        T_BOOLEAN_AND,
        T_BOOLEAN_OR,
        T_LOGICAL_AND,
        T_LOGICAL_OR
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_IF, T_ELSEIF];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $functionPosition = $phpcsFile->findNext(T_STRING, $stackPtr + 1, null, false, $this->functionName, true);
        if ($functionPosition !== false && array_key_exists('nested_parenthesis', $tokens[$functionPosition])) {
            $openParenthesisPosition = key($tokens[$functionPosition]['nested_parenthesis']);
            $endOfStatementPosition = $tokens[$openParenthesisPosition]['parenthesis_closer'];
            $nextOperatorPosition = $phpcsFile->findNext(
                $this->logicOperators,
                $functionPosition,
                $endOfStatementPosition
            );
            if ($nextOperatorPosition !== false) {
                $endOfStatementPosition = $nextOperatorPosition;
            }
            $operatorPosition = $phpcsFile->findNext(
                $this->comparisonOperators,
                $functionPosition,
                $endOfStatementPosition
            );
            if ($operatorPosition !== false) {
                if ($phpcsFile->findNext(T_LNUMBER, $operatorPosition, $endOfStatementPosition, false, '0') !== false) {
                    $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
                }
            } else {
                $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
            }
        }
    }
}
