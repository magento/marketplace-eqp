<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Templates;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class RawJavaScriptSniff
 * Detects possible usage of raw javascript in template files.
 */
class RawJavaScriptSniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'Missing JS component initialization. Use "x-magento-init" or "x-magento-template".';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundRawJS';

    /**
     * Script tag pattern.
     *
     * @var string
     */
    protected $scriptPattern = '<script';

    /**
     * Component initialization type.
     *
     * @var array
     */
    protected $componentInit = ['x-magento-init', 'x-magento-template'];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_INLINE_HTML];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $currentContent = $tokens[$stackPtr]['content'];
        if (strpos($currentContent, $this->scriptPattern) !== false && !$this->isInitialized($currentContent)) {
            $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
        }
    }

    /**
     * Check if one of attributes used in current content.
     *
     * @param string $currentContent
     * @return bool
     */
    private function isInitialized($currentContent)
    {
        foreach ($this->componentInit as $attribute) {
            if (strpos($currentContent, $attribute) !== false) {
                return true;
            }
        }
        return false;
    }
}
