<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Legacy_MageEntityUnitTest
 */
class MEQP2_Tests_Legacy_MageEntityUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            3 => 1,
            4 => 1,
            5 => 1,
            6 => 1,
            7 => 1,
            8 => 1,
            9 => 1
        ];

    }

    /**
     * @inheritdoc
     */
    public function getWarningList()
    {
        return [];
    }
}
