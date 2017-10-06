<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Classes;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class NameResolutionUnitTest
 */
class NameResolutionUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    protected function shouldSkipTest()
    {
        return Config::getConfigData('m2-path') === null;

    }

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
            6 => 1,
        ];
    }
}
