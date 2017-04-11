<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ConstructorOperationsSniff
 * Detects non-assignment operations in constructors.
 */
class ConstructorOperationsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * String representation of warning.
     *
     * @var string
     */
    // @codingStandardsIgnoreLine
    protected $warningMessage = 'Only dependency assignment operations are allowed in constructor. No other operations are allowed.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'CustomOperationsFound';

    /**
     * Allowed tokens in the constructor.
     *
     * @var array
     */
    protected $allowedTokens = [
        T_VARIABLE,
        T_STRING,
        T_OBJECT_OPERATOR,
        T_WHITESPACE,
        T_EQUAL,
        T_DOUBLE_COLON,
        T_SEMICOLON,
        T_OPEN_PARENTHESIS,
        T_CLOSE_PARENTHESIS,
        T_COMMENT,
        T_PARENT,
        T_STATIC,
        T_ARRAY,
        T_COMMA,
        T_TRUE,
        T_FALSE,
        T_OPEN_SHORT_ARRAY,
        T_CLOSE_SHORT_ARRAY,
        T_CLASS,
        T_CONSTANT_ENCAPSED_STRING,
        T_CONST,
        T_DOUBLE_ARROW,
    ];

    /**
     * Allowed prefixes for method names.
     *
     * @var array
     */
    protected $allowedPrefixes = [
        'get',
    ];

    /**
     * PHP_CodeSniffer file.
     *
     * @var PHP_CodeSniffer_File
     */
    private $file;

    /**
     * All tokens from current file.
     *
     * @var array
     */
    private $tokens;

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_CLASS];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->file = $phpcsFile;
        $tokens = $phpcsFile->getTokens();
        $this->tokens = $tokens;
        $constructorPos = $phpcsFile->findNext(T_STRING, $stackPtr + 1, null, false, '__construct');
        if ($constructorPos === false) {
            return;
        }
        $constructorStart = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $constructorPos + 1);
        $constructorEnd = $tokens[$constructorStart]['bracket_closer'];

        if ($phpcsFile->findNext($this->allowedTokens, $constructorStart + 1, $constructorEnd - 1, true) !== false) {
            $phpcsFile->addWarning($this->warningMessage, $constructorPos, $this->warningCode, [], $this->severity);
            return;
        }

        $start = $constructorStart + 1;
        while ($posOfSemicolon = $phpcsFile->findNext(T_SEMICOLON, $start, $constructorEnd)) {
            if (!$this->isExpressionAllowed($start, $posOfSemicolon + 1)) {
                $phpcsFile->addWarning($this->warningMessage, $constructorPos, $this->warningCode, [], $this->severity);
                return;
            }
            $start = $posOfSemicolon + 1;
        }
    }

    /**
     * Checks is expression allowed to be in the constructor.
     *
     * @param int $start
     * @param int $end
     * @return bool
     */
    private function isExpressionAllowed($start, $end)
    {
        $posOfEqual = $this->file->findNext(T_EQUAL, $start, $end);
        return ($posOfEqual === false)
            ? $this->isAllowedWithoutEqual($start, $end)
            : $this->isAllowedWithEqual($start, $end, $posOfEqual);
    }

    /**
     * Checks is assignment expression allowed to be in the constructor.
     *
     * @param int $start
     * @param int $end
     * @param int $posOfEqual
     * @return bool
     */
    private function isAllowedWithEqual($start, $end, $posOfEqual)
    {
        return $this->isAllowedBeforeEqual($start, $posOfEqual) && $this->isAllowedAfterEqual($posOfEqual, $end);
    }

    /**
     * Check is left part of the assignment expression is allowed to be in the constructor.
     *
     * @param int $start
     * @param int $posOfEqual
     * @return bool
     */
    private function isAllowedBeforeEqual($start, $posOfEqual)
    {
        $tVar = $this->file->findNext(T_WHITESPACE, $start, $posOfEqual, true);
        if ($tVar !== false
            && $this->tokens[$tVar]['type'] === 'T_VARIABLE'
            && $this->tokens[$tVar]['content'] === '$this') {
            $tObjectOperator = $this->file->findNext(T_WHITESPACE, $tVar + 1, $posOfEqual, true);
            if ($tObjectOperator !== false
                && $this->tokens[$tObjectOperator]['type'] === 'T_OBJECT_OPERATOR') {
                $tString = $this->file->findNext(T_WHITESPACE, $tObjectOperator + 1, $posOfEqual, true);
                if ($tString !== false
                    && $this->tokens[$tString]['type'] === 'T_STRING') {
                    $tEqual = $this->file->findNext(T_WHITESPACE, $tString + 1, $posOfEqual + 1, true);
                    if ($tEqual !== false
                        && $this->tokens[$tEqual]['type'] === 'T_EQUAL') {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Check is right part of the assignment expression is allowed to be in the constructor.
     *
     * @param int $posOfEqual
     * @param int $end
     * @return bool
     */
    private function isAllowedAfterEqual($posOfEqual, $end)
    {
        $parenthesis = $this->file->findNext(T_OPEN_PARENTHESIS, $posOfEqual, $end);
        if ($parenthesis !== false) {
            $tString = $this->file->findPrevious(T_WHITESPACE, $parenthesis - 1, $posOfEqual, true);
            if ($tString !== false
                && $this->tokens[$tString]['type'] === 'T_STRING') {
                $allowedFunction = false;
                foreach ($this->allowedPrefixes as $prefix) {
                    if (substr($this->tokens[$tString]['content'], 0, strlen($prefix)) === $prefix) {
                        $allowedFunction = true;
                    }
                }
                $tSemicolon = $this->file->findNext(
                    T_WHITESPACE,
                    $this->tokens[$parenthesis]['parenthesis_closer'] + 1,
                    $end,
                    true
                );
                if (!$allowedFunction
                    || $tSemicolon === false
                    || $this->tokens[$tSemicolon]['type'] !== 'T_SEMICOLON'
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check is non-assignment expression allowed to be in the constructor.
     *
     * @param int $start
     * @param int $end
     * @return bool
     */
    private function isAllowedWithoutEqual($start, $end)
    {
        $tParent = $this->file->findNext(T_PARENT, $start, $end);
        if ($tParent !== false) {
            $tString = $this->file->findNext(T_STRING, $tParent + 1, $end, false, '__construct');
            if ($tString !== false) {
                return true;
            }
        }
        return false;
    }
}
