<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Whitespace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;

/**
 * Class MultipleEmptyLinesSniff
 * Detects possible usage of multiple blank lines in a row.
 */
class MultipleEmptyLinesSniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'Code must not contain multiple empty lines in a row; found %s empty lines.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'MultipleEmptyLines';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_WHITESPACE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($phpcsFile->hasCondition($stackPtr, T_FUNCTION)
            || $phpcsFile->hasCondition($stackPtr, T_CLASS)
            || $phpcsFile->hasCondition($stackPtr, T_INTERFACE)
        ) {
            if ($tokens[$stackPtr - 1]['line'] < $tokens[$stackPtr]['line']
                && $tokens[$stackPtr - 2]['line'] === $tokens[$stackPtr - 1]['line']
            ) {
                // This is an empty line and the line before this one is not
                // empty, so this could be the start of a multiple empty line block
                $next = $phpcsFile->findNext(T_WHITESPACE, $stackPtr, null, true);
                $lines = $tokens[$next]['line'] - $tokens[$stackPtr]['line'];
                if ($lines > 1) {
                    $phpcsFile->addWarning(
                        $this->warningMessage,
                        $stackPtr,
                        $this->warningCode,
                        [$lines],
                        $this->severity
                    );
                }
            }
        }
    }
}
