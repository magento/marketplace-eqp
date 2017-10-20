<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\PHP;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class PrivateClassMemberSniff
 * Detects possible usage of 'private' scope modifiers.
 */
class PrivateClassMemberSniff implements Sniff
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
    protected $warningMessage = 'Use of private class members is discouraged.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundPrivate';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_PRIVATE];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
    }
}
