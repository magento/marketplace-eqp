<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Classes_NameResolutionUnitTest
 */
class MEQP2_Tests_Classes_NameResolutionUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    protected function shouldSkipTest()
    {
        return \PHP_CodeSniffer::getConfigData('m2-path') === null;

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
