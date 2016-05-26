<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Security;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;

/**
 * Class AclSniff
 * Detects possible improper usage of adminhtml actions.
 */
class AclSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of error.
     */
    protected $errorMessage = 'Missing the %s() ACL method in the %s class.';

    /**
     * Warning violation code.
     */
    protected $errorCode = 'MissingAclMethod';

    /**
     *  Expected controller parent class name.
     */
    protected $parentClassName = 'Mage_Adminhtml_Controller_Action';

    /**
     * Expected method presence.
     */
    protected $requiredAclMethodName = '_isAllowed';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_CLASS];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $classScopeStart = $tokens[$stackPtr]['scope_opener'];
        $classScopeEnd = $tokens[$stackPtr]['scope_closer'];
        $classPosition = $stackPtr;

        $stackPtr = $phpcsFile->findNext(T_STRING, ($stackPtr + 1));
        $className = $tokens[$stackPtr]['content'];

        if (false === ($stackPtr = $phpcsFile->findNext(T_EXTENDS, ($stackPtr + 1)))) {
            // the currently tested class hasn't extended any class
            return;
        }

        $stackPtr = $phpcsFile->findNext(T_STRING, ($stackPtr + 1));
        $parentClassName = $tokens[$stackPtr]['content'];

        if ($parentClassName === $this->parentClassName) {
            while (false !== ($stackPtr = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($classScopeStart + 1),
                ($classScopeEnd - 1),
                true,
                'function'
            )
            )
            ) {
                $stackPtr = $phpcsFile->findNext(T_STRING, ($stackPtr + 1));
                $methodName = $tokens[$stackPtr]['content'];
                $classScopeStart = $stackPtr;

                if ($methodName === $this->requiredAclMethodName) {
                    // the currently tested class has implemented the required ACL method
                    return;
                }
            }
            $data = [$this->requiredAclMethodName, $className];
            $phpcsFile->addError($this->errorMessage, $classPosition, $this->errorCode, $data);
        }
    }
}
