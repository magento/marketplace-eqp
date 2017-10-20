<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\PHP;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class SyntaxUnitTest
 */
class SyntaxUnitTest extends AbstractSniffUnitTest
{
    /**
     * Should this test be skipped for some reason.
     *
     * @return void
     */
    protected function shouldSkipTest()
    {
        $phpPath = Config::getConfigData('php7.0_path');
        return (is_null($phpPath));
    }

    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            21 => 1,
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
