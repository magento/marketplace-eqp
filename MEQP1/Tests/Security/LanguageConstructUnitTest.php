<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP1_Tests_Security_LanguageConstructUnitTest
 */
class MEQP1_Tests_Security_LanguageConstructUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList()
    {
        return [
            7 => 1,
            8 => 1,
            10 => 1,
            14 => 1,
            15 => 1,
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
