<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class AclUnitTest
 */
class AclUnitTest extends AbstractSniffUnitTest
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
