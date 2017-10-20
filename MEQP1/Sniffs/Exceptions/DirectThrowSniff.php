<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Exceptions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class DirectThrowSniff
 * Detects possible direct throws of Exceptions.
 */
class DirectThrowSniff implements Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 6;

    /**
     * String representation of warning.
     *
     * @var string
     */
    // @codingStandardsIgnoreLine
    protected $warningMessage = 'Direct throw of Exception does not allow manage messages. Use Mage::throwException() instead.';

    /**
     * Warning violation code.
     *
     * @var string
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
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $endOfStatement = $phpcsFile->findEndOfStatement($stackPtr);
        $posOfException = $phpcsFile->findNext(T_STRING, $stackPtr, $endOfStatement);
        if ($tokens[$posOfException]['content'] === 'Exception') {
            $phpcsFile->addWarning(
                $this->warningMessage,
                $stackPtr,
                $this->warningCode,
                $posOfException,
                $this->severity
            );
        }
    }
}
