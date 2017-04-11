<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Security;

/**
 * Class SuperglobalSniff
 * Detects possible usage of super global variables.
 */
class SuperglobalSniff extends \MEQP1\Sniffs\Security\SuperglobalSniff
{
    /**
     * @inheritdoc
     */
    protected $superGlobalErrors = [
        '$GLOBALS',
        '$_GET',
        '$_POST',
        '$_SESSION',
        '$_REQUEST',
        '$_ENV',
        '$_FILES',
    ];

    /**
     * @inheritdoc
     */
    protected $superGlobalWarning = [
        '$_COOKIE', //sometimes need to  get list of all cookies array and there are no methods to do that in M2
        '$_SERVER',
    ];
}
