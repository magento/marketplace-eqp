<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_CollectionDependencyUnitTest
 */
class MEQP2_Tests_Classes_CollectionDependencyUnitTest extends AbstractSniffUnitTest
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
            2 => 1,
            5 => 1,
        ];
    }
}
