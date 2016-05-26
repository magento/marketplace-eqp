<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Exceptions;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class DirectThrowSniff
 * Detects possible direct throws of Exceptions.
 */
class DirectThrowSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * String representation of warning.
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = 'Direct throw of Exception does not allow manage messages.Use Mage::throwException() instead.';
    // @codingStandardsIgnoreEnd

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundDirectThrow';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_THROW];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $endOfStatement = $phpcsFile->findEndOfStatement($stackPtr);
        $posOfException = $phpcsFile->findNext(T_STRING, $stackPtr, $endOfStatement);
        if ($tokens[$posOfException]['content'] === 'Exception') {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, $posOfException);
        }
    }
}
