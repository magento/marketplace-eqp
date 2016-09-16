<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Security;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class LanguageConstructSniff
 * Detects possible usage of discouraged language constructs.
 */
class LanguageConstructSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of error.
     */
    protected $errorMessage = 'Use of %s language construct is discouraged.';

    /**
     * String representation of error.
     */
    // @codingStandardsIgnoreStart
    protected $errorMessageBacktick = 'Incorrect usage of back quote string constant. Back quotes should be always inside strings.';
    // @codingStandardsIgnoreEnd

    /**
     * Error violation code.
     */
    protected $errorCode = 'WrongBackQuotesUsage';

    /**
     * Violation severity.
     */
    protected $severity = 10;

    /**
     * Exit usage code.
     */
    protected $exitUsage = 'ExitUsage';

    /**
     * Direct output code.
     */
    protected $directOutput = 'DirectOutput';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_EXIT,
            T_ECHO,
            T_PRINT,
            T_BACKTICK,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] === T_BACKTICK) {
            if ($phpcsFile->findNext(T_BACKTICK, ($stackPtr + 1))) {
                return;
            }
            $phpcsFile->addError($this->errorMessageBacktick, $stackPtr, $this->errorCode, [], $this->severity);
            return;
        }

        if ($tokens[$stackPtr]['code'] === T_EXIT) {
            $code = $this->exitUsage;
        } else {
            $code = $this->directOutput;
        }
        $phpcsFile->addError($this->errorMessage, $stackPtr, $code, [$tokens[$stackPtr]['content']], $this->severity);
    }
}
