<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\PHP;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class VarSniff
 * Detects possible usage of 'var' language construction.
 */
class VarSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Use of var class variables is discouraged.';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundVar';

    /**
     * Violation severity.
     */
    protected $severity = 8;

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_VAR];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
    }
}
