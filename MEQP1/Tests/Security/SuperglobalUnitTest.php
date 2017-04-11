<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_Security_SuperglobalUnitTest
 */
class MEQP1_Tests_Security_SuperglobalUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            12 => 1,
            15 => 1,
            17 => 1,
            20 => 1,
            21 => 1,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWarningList()
    {
        return [
            16 => 1,
            18 => 1,
            19 => 1,
        ];
    }
}
