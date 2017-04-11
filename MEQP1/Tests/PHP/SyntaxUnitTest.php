<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_PHP_SyntaxUnitTest
 */
class MEQP1_Tests_PHP_SyntaxUnitTest extends AbstractSniffUnitTest
{
    /**
     * Should this test be skipped for some reason.
     *
     * @return void
     */
    protected function shouldSkipTest()
    {
        $phpPath = PHP_CodeSniffer::getConfigData('php5.4_path');
        return (is_null($phpPath));
    }

    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            4 => 1,
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
