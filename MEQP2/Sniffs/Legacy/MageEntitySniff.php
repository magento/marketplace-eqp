<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Legacy;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class MageEntitySniff
 * Detects typical Magento 1 construction such as 'Mage::'.
 */
class MageEntitySniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Possible Magento 2 design violation. Detected typical Magento 1 construction.';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'FoundLegacyEntity';

    /**
     * Legacy entities from Magento 1.
     *
     * @var array
     */
    protected $legacyEntities = ['Mage'];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_DOUBLE_COLON];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $entityPosition = $phpcsFile->findPrevious(T_STRING, $stackPtr - 1);
        if (in_array($tokens[$entityPosition]['content'], $this->legacyEntities)) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode);
        }
    }
}
