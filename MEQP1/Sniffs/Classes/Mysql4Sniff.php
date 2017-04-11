<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class Mysql4Sniff
 * Detects usage of deprecated 'Mysql4' suffix in class names.
 */
class Mysql4Sniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'Mysql4 classes are obsolete.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundMysql4';

    /**
     * What to find in the class name.
     *
     * @var string
     */
    protected $deprecatedSuffix = 'Mysql4';

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
        $check = function ($ptr) use ($phpcsFile) {
            if (strpos($phpcsFile->getTokens()[$ptr]['content'], $this->deprecatedSuffix) !== false) {
                $phpcsFile->addWarning($this->warningMessage, $ptr, $this->warningCode, [], $this->severity);
                return true;
            }
            return false;
        };
        $next = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        $res = $check($next);
        if (!$res) {
            $extends = $phpcsFile->findNext(T_EXTENDS, $next + 1);
            if ($extends !== false) {
                $afterExtends = $phpcsFile->findNext(T_STRING, $extends + 1);
                $check($afterExtends);
            }
        }
    }
}
