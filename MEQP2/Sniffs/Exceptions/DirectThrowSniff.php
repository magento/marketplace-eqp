<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Exceptions;

/**
 * Class DirectThrowSniff
 * Detects possible direct throws of Exceptions.
 */
class DirectThrowSniff extends \MEQP1\Sniffs\Exceptions\DirectThrowSniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * String representation of warning.
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = 'Direct throw of Exception is discouraged. Use \Magento\Framework\Exception\LocalizedException instead.';
    // @codingStandardsIgnoreEnd
}
