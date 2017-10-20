<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Tests\Stdlib;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class DateTimeUnitTest
 */
class DateTimeUnitTest extends AbstractSniffUnitTest
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
            3 => 1,
            4 => 1,
            5 => 1,
            7 => 1,
            8 => 1,
            9 => 1,
            10 => 1,
            11 => 1,
            12 => 1,
            13 => 1,
        ];
    }
}
