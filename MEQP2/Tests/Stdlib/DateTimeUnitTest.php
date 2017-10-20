<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Stdlib;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class DateTimeUnitTest
 */
class DateTimeUnitTest extends AbstractSniffUnitTest
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
            21 => 1,
            26 => 1
        ];
    }
}
