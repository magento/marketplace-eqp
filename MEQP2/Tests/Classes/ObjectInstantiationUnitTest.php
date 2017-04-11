<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_ObjectInstantiationUnitTest
 */
class MEQP2_Tests_Classes_ObjectInstantiationUnitTest extends AbstractSniffUnitTest
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
            20 => 1,
            22 => 1,
            23 => 1,
        ];
    }
}
