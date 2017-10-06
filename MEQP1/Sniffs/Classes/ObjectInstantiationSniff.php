<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class ObjectInstantiationSniff
 * Detects direct object instantiation via 'new' keyword.
 */
class ObjectInstantiationSniff implements Sniff
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
     * Class part which is allowed to use with 'Mage_' and 'Enterprise_' prefixes.
     *
     * @var string
     */
    protected $allowedClassPart = 'Exception';

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
    public function process(File $phpcsFile, $stackPtr)
    {
        $next = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        $className = $phpcsFile->getTokens()[$next]['content'];
        if (preg_match('/^(' . implode(
            '|',
            $this->disallowedClassPrefixes
        ) . ')((?!' . $this->allowedClassPart . ').)*$/i', $className)) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [$className], $this->severity);
        }
    }
}
