<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Tests\SQL;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class RawQueryUnitTest
 */
class RawQueryUnitTest extends AbstractSniffUnitTest
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
            28 => 1,
            37 => 1,
            46 => 1,
            54 => 1,
            60 => 1,
            75 => 1,
            97 => 1,
            101 => 1,
            104 => 1,
        ];
    }

    /**
     * @inheritdoc
     */
    public function shouldSkipTest()
    {
        return true;
    }
}
