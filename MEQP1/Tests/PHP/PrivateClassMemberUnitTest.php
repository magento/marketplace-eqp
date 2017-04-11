<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_PHP_PrivateClassMemberUnitTest
 */
class MEQP1_Tests_PHP_PrivateClassMemberUnitTest extends AbstractSniffUnitTest
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
            8 => 1,
            20 => 1,
        ];
    }
}
