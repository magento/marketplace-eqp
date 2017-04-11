<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_Security_AclUnitTest
 */
class MEQP1_Tests_Security_AclUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getWarningList()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            11 => 1
        ];
    }
}
