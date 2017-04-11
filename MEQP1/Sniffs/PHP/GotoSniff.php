<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\PHP;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class GotoSniff
 * Detects use of GOTO.
 */
class GotoSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 10;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $errorMessage = 'Use of goto is discouraged.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $errorCode = 'FoundGoto';

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
        $phpcsFile->addError($this->errorMessage, $stackPtr, $this->errorCode, [], $this->severity);
    }
}
