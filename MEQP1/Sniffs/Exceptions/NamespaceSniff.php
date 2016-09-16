<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Exceptions;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class NamespaceSniff
 * Detects possible usage of exceptions without namespace declaration.
 */
class NamespaceSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * String representation of error.
     */
    protected $errorMessage = 'Namespace for %s class is not specified.';

    /**
     * Error violation code.
     */
    protected $errorCode = 'NotFoundNamespace';

    /**
     * Violation severity.
     */
    protected $severity = 10;

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_CATCH, T_THROW];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($phpcsFile->findNext(T_NAMESPACE, 0) === false) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $endOfStatement = $phpcsFile->findEndOfStatement($stackPtr);
        $posOfExceptionClassName = $phpcsFile->findNext(T_STRING, $stackPtr, $endOfStatement);
        $posOfNsSeparator = $phpcsFile->findNext(T_NS_SEPARATOR, $stackPtr, $posOfExceptionClassName);
        if ($posOfNsSeparator === false && $posOfExceptionClassName !== false) {
            $exceptionClassName = trim($tokens[$posOfExceptionClassName]['content']);
            $posOfClassInUse = $phpcsFile->findNext(T_STRING, 0, $stackPtr, false, $exceptionClassName);
            if ($posOfClassInUse === false || $tokens[$posOfClassInUse]['level'] != 0) {
                $phpcsFile->addError($this->errorMessage, $stackPtr, $this->errorCode, $exceptionClassName, $this->severity);
            }
        }
    }
}
