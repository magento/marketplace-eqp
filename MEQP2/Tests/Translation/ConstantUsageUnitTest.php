<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Translation_ConstantUsageUnitTest
 */
class MEQP2_Tests_Translation_ConstantUsageUnitTest extends AbstractSniffUnitTest
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
            9 => 1,
            11 => 1,
            12 => 1,
            15 => 1,
        ];
    }
}
