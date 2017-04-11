<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ObjectInstantiationSniff
 * Detects direct object instantiation via 'new' keyword.
 */
class ObjectInstantiationSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = 'Direct object instantiation (class %s) is discouraged in Magento.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundDirectInstantiation';

    /**
     * List of class prefixes which shouldn't be instantiated with 'new' keyword.
     *
     * @var array
     */
    protected $disallowedClassPrefixes = [
        'Mage_',
        'Enterprise_',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_NEW];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $next = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        $className = $phpcsFile->getTokens()[$next]['content'];
        if (preg_match('/^(' . implode('|', $this->disallowedClassPrefixes) . ')/i', $className)) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [$className], $this->severity);
        }
    }
}
