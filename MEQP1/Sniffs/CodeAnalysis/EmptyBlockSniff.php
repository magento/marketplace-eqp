<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\CodeAnalysis;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use Generic_Sniffs_CodeAnalysis_EmptyStatementSniff;

/**
 * Class EmptyBlockSniff
 * Detects possible empty blocks.
 */
class EmptyBlockSniff extends Generic_Sniffs_CodeAnalysis_EmptyStatementSniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return array_merge(
            parent::register(),
            [
                T_CLASS,
                T_ABSTRACT,
                T_FUNCTION,
                T_INTERFACE,
                T_TRAIT
            ]
        );
    }
}
