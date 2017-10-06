<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class ConstructorOperationsUnitTest
 */
class ConstructorOperationsUnitTest extends AbstractSniffUnitTest
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
