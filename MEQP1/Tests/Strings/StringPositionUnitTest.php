<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_Strings_StringPositionUnitTest
 */
class MEQP1_Tests_Strings_StringPositionUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            4 => 1,
            8 => 1,
            12 => 1,
            16 => 1,
            20 => 1,
            24 => 1,
            32 => 1,
            40 => 1,
            44 => 1,
            72 => 1,
            76 => 1,
            80 => 2,
            85 => 1,
            87 => 1,
            98 => 1,
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
