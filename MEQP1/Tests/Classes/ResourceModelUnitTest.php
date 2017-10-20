<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class ResourceModelUnitTest
 */
class ResourceModelUnitTest extends AbstractSniffUnitTest
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
            10 => 1,
            11 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            19 => 1,
            20 => 1,
            25 => 1,
            26 => 1,
            29 => 1,
            30 => 1,
            33 => 1,
            40 => 1
        ];
    }
}
