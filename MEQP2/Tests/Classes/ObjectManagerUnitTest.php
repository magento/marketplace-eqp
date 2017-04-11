<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_ObjectManagerUnitTest
 */
class MEQP2_Tests_Classes_ObjectManagerUnitTest extends AbstractSniffUnitTest
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
            5 => 1,
            7 => 1,
            9 => 1,
            12 => 1,
            14 => 1,
            16 => 1,
            20 => 1,
            23 => 1,
        ];
    }
}
