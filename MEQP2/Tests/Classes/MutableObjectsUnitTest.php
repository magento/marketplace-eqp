<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_ClassesMutableObjectsUnitTest
 */
class MEQP2_Tests_Classes_MutableObjectsUnitTest extends AbstractSniffUnitTest
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
            26 => 1,
            27 => 1,
            31 => 1,
            33 => 1,
            34 => 1,
            70 => 1
        ];
    }
}
