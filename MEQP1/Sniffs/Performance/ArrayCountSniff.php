<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Performance;

use \AbstractSniffs\AbstractEmptyCheckSniff;

/**
 * Class ArrayCountSniff
 */
class ArrayCountSniff extends AbstractEmptyCheckSniff
{
    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = 'count(...) function should not be used to check if array is empty. Use empty(...) language construct instead';
    // @codingStandardsIgnoreEnd

    /**
     * @inheritdoc
     */
    protected $warningCode = 'FoundCount';

    /**
     * @inheritdoc
     */
    protected $functionName = 'count';
}
