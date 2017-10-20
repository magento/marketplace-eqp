<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class PublicNonInterfaceMethodsUnitTest
 */
class PublicNonInterfaceMethodsUnitTest extends AbstractSniffUnitTest
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
    public function getWarningList($testFile = '')
    {
        switch ($testFile) {
            case 'PublicNonInterfaceMethodsUnitTest.Controller.inc':
                return [
                    23 => 1,
                ];
                break;
            case 'PublicNonInterfaceMethodsUnitTest.Observer.inc':
                return [
                    18 => 1,
                ];
                break;
            case 'PublicNonInterfaceMethodsUnitTest.Abstract.inc':
            default:
                return [];
                break;
        }
    }
}
