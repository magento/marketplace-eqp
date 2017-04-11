<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Performance;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class CollectionCountSniff
 * Detects possible usage of collection count() method.
 */
class CollectionCountSniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'Unnecessary loading of a Magento data collection. Use the getSize() method instead.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundCollectionCount';

    /**
     * Observed methods.
     *
     * @var array
     */
    protected $methods = ['count'];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (!in_array($tokens[$stackPtr]['content'], $this->methods)) {
            return;
        }
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if ($tokens[$prevToken]['code'] !== T_OBJECT_OPERATOR) {
            return;
        }
        $prevPrevToken = $phpcsFile->findPrevious(
            [
                T_WHITESPACE,
                T_OPEN_PARENTHESIS,
                T_CLOSE_PARENTHESIS,
            ],
            $prevToken - 1,
            null,
            true
        );
        if (($tokens[$prevPrevToken]['code'] === T_VARIABLE || $tokens[$prevPrevToken]['code'] === T_STRING)
            && stripos($tokens[$prevPrevToken]['content'], 'collection') !== false
        ) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
        }
    }
}
