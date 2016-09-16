<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Performance;

use \AbstractSniffs\AbstractEmptyCheckSniff;

/**
 * Class StrlenSniff
 */
class StrlenSniff extends AbstractEmptyCheckSniff
{
    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = 'strlen(...) function should not be used to check if string is empty. Consider replace with $... (=/!)== ""';
    // @codingStandardsIgnoreEnd

    /**
     * @inheritdoc
     */
    protected $warningCode = 'FoundStrlen';

    /**
     * @inheritdoc
     */
    protected $functionName = 'strlen';
}
