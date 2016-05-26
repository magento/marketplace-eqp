<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Strings;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class StringPositionSniff
 * Detects misusing of IS_IDENTICAL operators.
 */
class StringPositionSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Identical operator === is not used for testing the return value of %s function';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'ImproperValueTesting';

    /**
     * Searched functions.
     *
     * @var array
     */
    protected $functions = [
        'strpos',
        'stripos',
    ];

    /**
     * All tokens of page.
     *
     * @var array
     */
    protected $tokens = [];

    /**
     * Left limit for search of identical operators.
     *
     * @var int
     */
    protected $leftLimit;

    /**
     * Right limit for search of identical operators.
     *
     * @var int
     */
    protected $rightLimit;

    /**
     * List of tokens which declares left bound of current scope.
     *
     * @var array
     */
    protected $leftRangeTokens = [
        T_IS_IDENTICAL,
        T_IS_NOT_IDENTICAL,
        T_OPEN_PARENTHESIS,
        T_BOOLEAN_AND,
        T_BOOLEAN_OR
    ];

    /**
     * List of tokens which declares right bound of current scope.
     *
     * @var array
     */
    protected $rightRangeTokens = [
        T_IS_IDENTICAL,
        T_IS_NOT_IDENTICAL,
        T_CLOSE_PARENTHESIS,
        T_BOOLEAN_AND,
        T_BOOLEAN_OR
    ];

    /**
     * List of tokens which declares identical operators.
     *
     * @var array
     */
    protected $identical = [
        T_IS_IDENTICAL,
        T_IS_NOT_IDENTICAL
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
        $this->tokens = $phpcsFile->getTokens();

        $this->leftLimit = $open = $this->tokens[$stackPtr]['parenthesis_opener'];
        $this->rightLimit = $close = $this->tokens[$stackPtr]['parenthesis_closer'];

        for ($i = ($open + 1); $i < $close; $i++) {
            if (($this->tokens[$i]['code'] === T_STRING && in_array($this->tokens[$i]['content'], $this->functions))
                && (!$this->findIdentical($i - 1, $phpcsFile->findNext(T_CLOSE_PARENTHESIS, $i, $close) + 1))
            ) {
                $foundFunctionName = $this->tokens[$i]['content'];
                $phpcsFile->addWarning($this->warningMessage, $i, $this->warningCode, [$foundFunctionName]);
            }
        }
    }

    /**
     * Recursively finds identical operators in current scope.
     *
     * @param int $leftCurrentPosition
     * @param int $rightCurrentPosition
     * @return bool
     */
    protected function findIdentical($leftCurrentPosition, $rightCurrentPosition)
    {
        $leftBound = $this->getLeftBound($leftCurrentPosition);
        $rightBound = $this->getRightBound($rightCurrentPosition);
        $leftToken = $this->tokens[$leftBound];
        $rightToken = $this->tokens[$rightBound];
        if ($leftToken['code'] === T_OPEN_PARENTHESIS && $rightToken['code'] === T_CLOSE_PARENTHESIS) {
            return $this->findIdentical($leftBound - 1, $rightBound + 1);
        } else {
            return (
                in_array($leftToken['code'], $this->identical) || in_array($rightToken['code'], $this->identical)
            ) ?: false;
        }
    }

    /**
     * Get left bound position of current scope.
     *
     * @param int $currentPosition
     * @return int
     */
    protected function getLeftBound($currentPosition)
    {
        while (!in_array($this->tokens[$currentPosition]['code'], $this->leftRangeTokens)
            && $currentPosition > $this->leftLimit) {
            $currentPosition--;
        }
        return $currentPosition;
    }

    /**
     * Get right bound position of current scope.
     *
     * @param int $currentPosition
     * @return int
     */
    protected function getRightBound($currentPosition)
    {
        while (!in_array($this->tokens[$currentPosition]['code'], $this->rightRangeTokens)
            && $currentPosition < $this->rightLimit) {
            $currentPosition++;
        }
        return $currentPosition;
    }
}
