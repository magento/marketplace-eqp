<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Strings;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;

/**
 * Class StringConcatSniff
 * Detects string concatenation via '+' operator.
 */
class StringConcatSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of error.
     */
    protected $errorMessage = 'Use of + operator to concatenate two strings detected';

    /**
     * Error violation code.
     */
    protected $errorCode = 'ImproperStringConcatenation';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_PLUS];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $prev = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if ($prev === false || $next === false) {
            return;
        }

        $beforePrev = $phpcsFile->findPrevious(T_WHITESPACE, ($prev - 1), null, true);

        $stringTokens = PHP_CodeSniffer_Tokens::$stringTokens;
        if ($tokens[$beforePrev]['code'] === T_STRING_CONCAT
            || in_array($tokens[$prev]['code'], $stringTokens)
            || in_array($tokens[$next]['code'], $stringTokens)
        ) {
            $phpcsFile->addError($this->errorMessage, $stackPtr, $this->errorCode);
        }
    }
}
