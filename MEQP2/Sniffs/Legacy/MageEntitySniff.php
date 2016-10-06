<?php
/**
 * Copyright � 2016 Magento. All rights reserved.
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
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 10;

    /**
     * String representation of error.
     *
     * @var string
     */
    protected $errorMessage = 'Possible Magento 2 design violation. Detected typical Magento 1 construction "%s".';

    /**
     * Error violation code.
     *
     * @var string
     */
    protected $errorCode = 'FoundLegacyEntity';

    /**
     * Legacy entity from Magento 1.
     *
     * @var string
     */
    protected $legacyEntity = 'Mage';

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
        $entityName = $tokens[$phpcsFile->findPrevious(T_STRING, $stackPtr - 1)]['content'];
        if ($entityName === $this->legacyEntity) {
            $phpcsFile->addError(
                $this->errorMessage,
                $stackPtr,
                $this->errorCode,
                [$entityName . '::'],
                $this->severity
            );
        }
    }
}
