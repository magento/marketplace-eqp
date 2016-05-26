<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Templates;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ThisInTemplateSniff
 * Detects possible usage of $this variable files.
 */
class ThisInTemplateSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Usage of $this in template files is deprecated.';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundThis';

    /**
     * List of methods, allowed to called via $this.
     *
     * @var array
     */
    protected $allowedMethods = [
        'helper',
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
        if ($tokens[$stackPtr]['content'] === '$this') {
            $endOfStatementPtr = $phpcsFile->findEndOfStatement($stackPtr);
            $functionPtr = $phpcsFile->findNext(T_STRING, $stackPtr, $endOfStatementPtr);
            if ($functionPtr) {
                if (!in_array($tokens[$functionPtr]['content'], $this->allowedMethods)) {
                    $phpcsFile->addWarning($this->warningMessage, $stackPtr);
                }
            } else {
                $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
            }
        }
    }
}
