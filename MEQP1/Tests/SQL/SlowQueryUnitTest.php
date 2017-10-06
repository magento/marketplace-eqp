<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Tests\SQL;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class SlowQueryUnitTest
 */
class SlowQueryUnitTest extends AbstractSniffUnitTest
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
            7 => 1,
            12 => 1,
            22 => 1,
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
