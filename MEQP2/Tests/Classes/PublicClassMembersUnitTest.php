<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class PublicClassMembersUnitTest
 */
class PublicClassMembersUnitTest extends AbstractSniffUnitTest
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
            case 'PublicClassMembersUnitTest.Controller.inc':
                return [
                    23 => 1,
                    33 => 1,
                ];
                break;
            case 'PublicClassMembersUnitTest.Observer.inc':
                return [
                    18 => 1,
                    23 => 1,
                ];
                break;
            case 'PublicClassMembersUnitTest.Abstract.inc':
            default:
                return [];
                break;
        }
    }
}
