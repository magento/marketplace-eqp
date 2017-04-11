<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_SQL_CoreTablesModificationUnitTest
 */
class MEQP2_Tests_SQL_CoreTablesModificationUnitTest extends AbstractSniffUnitTest
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
            18 => 1,
            23 => 1,
            28 => 1,
            34 => 1,
            39 => 1,
            41 => 1,
            44 => 1,
            48 => 1,
            55 => 1,
            60 => 1,
            64 => 1,
        ];
    }
}