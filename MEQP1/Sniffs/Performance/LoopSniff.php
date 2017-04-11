<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Performance;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class LoopSniff
 * Detects possible data load in the loop.
 */
class LoopSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * List of 'heavy' functions.
     *
     * @var array
     */
    protected $countFunctions = [
        'sizeof',
        'count',
    ];

    /**
     * List of data-loading model methods.
     *
     * @var array
     */
    protected $modelLsdMethods = [
        'load',
        'save',
        'delete',
    ];

    /**
     * List of data-loading methods.
     *
     * @var array
     */
    protected $dataLoadMethods = [
        'getFirstItem',
        'getChildrenIds',
        'getParentIdsByChild',
        'getEditableAttributes',
        'getUsedProductAttributeIds',
        'getUsedProductAttributes',
        'getConfigurableAttributes',
        'getConfigurableAttributesAsArray',
        'getConfigurableAttributeCollection',
        'getUsedProductIds',
        'getUsedProducts',
        'getUsedProductCollection',
        'getProductByAttributes',
        'getSelectedAttributesInfo',
        'getOrderOptions',
        'getConfigurableOptions',
        'getAssociatedProducts',
        'getAssociatedProductIds',
        'getAssociatedProductCollection',
        'getProductsToPurchaseByReqGroups',
        'getIdBySku',
    ];

    /**
     * Cache of processed pointers to prevent duplicates in case of nested loops.
     *
     * @var array
     */
    protected $processedStackPointers = [];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_WHILE,
            T_FOR,
            T_FOREACH,
            T_DO,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (!array_key_exists('scope_opener', $tokens[$stackPtr])) {
            return;
        }
        for ($ptr = $tokens[$stackPtr]['scope_opener'] + 1; $ptr < $tokens[$stackPtr]['scope_closer']; $ptr++) {
            $content = $tokens[$ptr]['content'];
            if ($tokens[$ptr]['code'] !== T_STRING || in_array($ptr, $this->processedStackPointers)) {
                continue;
            }
            $warning = '';
            $code = '';
            if (in_array($content, $this->countFunctions)) {
                $warning = 'Array size calculation function %s detected in loop';
                $code = 'ArraySize';
            } elseif (in_array($content, $this->modelLsdMethods)) {
                $warning = 'Model LSD method %s detected in loop';
                $code = 'ModelLSD';
            } elseif (in_array($content, $this->dataLoadMethods)) {
                $warning = 'Data load %s method detected in loop';
                $code = 'DataLoad';
            }
            if ($warning) {
                $phpcsFile->addWarning($warning, $ptr, $code, [$content . '()'], $this->severity);
                $this->processedStackPointers[] = $ptr;
            }
        }
    }
}
