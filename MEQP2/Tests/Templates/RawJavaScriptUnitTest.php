<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Templates;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class RawJavaScriptUnitTest
 */
class RawJavaScriptUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getWarningList()
    {
        return [
            6 => 1,
            10 => 1,
        ];
    }
}
