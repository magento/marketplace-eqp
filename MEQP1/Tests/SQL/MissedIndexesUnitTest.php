<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_SQL_MissedIndexesUnitTest
 */
class MEQP1_Tests_SQL_MissedIndexesUnitTest extends AbstractSniffUnitTest
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
        return [1 => 1];
    }

    /**
     * @inheritdoc
     */
    public function shouldSkipTest()
    {
        return true;
    }
}
