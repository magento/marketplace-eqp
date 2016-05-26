<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_InterfaceNameUnitTest
 */
class MEQP2_Tests_Classes_InterfaceNameUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            2 => 1,
            4 => 1,
            5 => 1,
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