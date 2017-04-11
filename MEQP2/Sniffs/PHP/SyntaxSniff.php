<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\PHP;

use PHP_CodeSniffer as Sniffer;
use PHP_CodeSniffer_File as File;
use Generic_Sniffs_PHP_SyntaxSniff as GenericSyntax;
use MEQP1\Sniffs\PHP\SyntaxSniff as M1Sniff;

/**
 * Class SyntaxSniff
 * Ensures PHP believes the syntax is clean.
 */
class SyntaxSniff extends M1Sniff
{
    /**
     * @param File $phpcsFile
     * @param $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpPath = Sniffer::getConfigData('php7.0_path');
        $this->execute($phpcsFile, $phpPath);
    }//end process()
}
