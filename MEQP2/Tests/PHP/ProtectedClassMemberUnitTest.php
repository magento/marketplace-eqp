<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_PHP_ProtectedClassMemberUnitTest
 */
class MEQP2_Tests_PHP_ProtectedClassMemberUnitTest extends AbstractSniffUnitTest
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
            7 => 1,
            15 => 1,
        ];
    }
}
