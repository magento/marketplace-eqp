<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_Templates_XssTemplateUnitTest
 */
class MEQP1_Tests_Templates_XssTemplateUnitTest extends AbstractSniffUnitTest
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
            3 => 2,
            4 => 1,
            5 => 1,
            6 => 1,
            7 => 1,
            8 => 1,
            9 => 1,
            10 => 1,
            13 => 1,
            14 => 1,
            16 => 1,
            17 => 1,
            18 => 2,
            19 => 2,
            20 => 2,
            21 => 1,
            22 => 1,
            23 => 1,
            24 => 1,
            28 => 2,
            29 => 1,
            30 => 2,
        ];
    }
}
