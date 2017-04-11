<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Templates;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class XssTemplateSniff
 * Detects not escaped output in phtml templates.
 */
class XssTemplateSniff extends \MEQP1\Sniffs\Templates\XssTemplateSniff
{

    /**
     * Magento escape methods.
     *
     * @var array
     */
    protected $allowedMethods = [
        'escapeUrl',
        'escapeJsQuote',
        'escapeQuote',
        'escapeXssInUrl',
    ];

    /**
     * Allowed method name - {suffix}Html{postfix}()
     *
     * @var string
     */
    protected $methodNameContains = 'html';

    /**
     * PHP functions, that no need escaping.
     *
     * @var array
     */
    protected $allowedFunctions = ['count'];

    /**
     * Allowed annotations.
     *
     * @var string
     */
    protected $allowedAnnotations = [
        '@noEscape',
    ];
}
