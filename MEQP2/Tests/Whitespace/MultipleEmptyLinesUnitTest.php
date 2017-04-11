<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Class MEQP2_Tests_Whitespace_MultipleEmptyLinesUnitTest
 */
class MEQP2_Tests_Whitespace_MultipleEmptyLinesUnitTest extends AbstractSniffUnitTest
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
            6 => 1,
            12 => 1,
        ];
    }
}
