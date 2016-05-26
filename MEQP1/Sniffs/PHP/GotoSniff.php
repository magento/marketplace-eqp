<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\PHP;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class GotoSniff
 * Detects possible usage of GOTO.
 */
class GotoSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Use of goto is discouraged.';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundGoto';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_GOTO];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
    }
}
