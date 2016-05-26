<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\PHP;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ProtectedClassMemberSniff
 * Detects possible usage of 'private' scope modifiers.
 */
class ProtectedClassMemberSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Use of protected class members is discouraged.';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundProtected';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_PROTECTED];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
    }
}
