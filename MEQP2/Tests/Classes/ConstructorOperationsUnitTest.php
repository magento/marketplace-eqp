<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_ConstructorOperationsUnitTest
 */
class MEQP2_Tests_Classes_ConstructorOperationsUnitTest extends AbstractSniffUnitTest
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
            18 => 1,
            28 => 1,
            36 => 1,
            44 => 1,
            53 => 1,
            64 => 1,
            78 => 1,
            92 => 1,
            105 => 1,
            117 => 1,
        ];
    }
}
