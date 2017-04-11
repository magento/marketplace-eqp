<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Security;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class SuperglobalSniff
 * Detects usage of super global variables.
 */
class SuperglobalSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity for error.
     *
     * @var int
     */
    protected $errorSeverity = 10;

    /**
     * Violation severity for warning.
     *
     * @var int
     */
    protected $warningSeverity = 6;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = 'Direct use of %s Superglobal detected.';

    /**
     * String representation of error.
     *
     * @var string
     */
    protected $errorMessage = 'Direct use of %s Superglobal detected.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'SuperglobalUsageWarning';

    /**
     * Error violation code.
     *
     * @var string
     */
    protected $errorCode = 'SuperglobalUsageError';

    /**
     * List of error variables.
     *
     * @var array
     */
    protected $superGlobalErrors = [
        '$GLOBALS',
        '$_GET',
        '$_POST',
        '$_SESSION',
        '$_REQUEST',
        '$_ENV',
    ];

    /**
     * List of warning variables.
     *
     * @var array
     */
    protected $superGlobalWarning = [
        '$_FILES',
        '$_COOKIE',
        '$_SERVER',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_VARIABLE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $var = $tokens[$stackPtr]['content'];
        if (in_array($var, $this->superGlobalErrors)) {
            $phpcsFile->addError(
                $this->errorMessage,
                $stackPtr,
                $this->errorCode,
                [$var],
                $this->errorSeverity
            );
        } elseif (in_array($var, $this->superGlobalWarning)) {
            $phpcsFile->addWarning(
                $this->warningMessage,
                $stackPtr,
                $this->warningCode,
                [$var],
                $this->warningSeverity
            );
        }
    }
}
