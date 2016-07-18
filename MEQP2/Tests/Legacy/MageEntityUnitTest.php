<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
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
        return [];

    }

    /**
     * @inheritdoc
     */
    public function getWarningList()
    {
        return [
            3 => 1,
            4 => 1,
        ];
    }
}
