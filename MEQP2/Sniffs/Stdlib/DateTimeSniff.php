<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Stdlib;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class DateTimeSniff
 * Detects overcomplicated Date/Time handling.
 */
class DateTimeSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * String representation of warning.
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = 'Overcomplicated Date/Time handling. Use \Magento\Framework\Stdlib\DateTime\TimezoneInterface instead.';
    // @codingStandardsIgnoreEnd

    /**
     * Warning violation code.
     */
    protected $warningCode = 'Overcomplicated';

    /**
     * Class name to find.
     */
    protected $dateTimeClass = [
        'DateTime',
        'DateTimeZone',
        'Zend_Date',
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
        $tokens = $phpcsFile->getTokens();
        $posOfClassName = $phpcsFile->findNext(T_STRING, $stackPtr);
        $posOfNsSeparator = $phpcsFile->findNext(T_NS_SEPARATOR, $stackPtr, $posOfClassName);
        if ($posOfNsSeparator !== false && in_array($tokens[$posOfClassName]['content'], $this->dateTimeClass)) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, $stackPtr);
        }
    }
}
