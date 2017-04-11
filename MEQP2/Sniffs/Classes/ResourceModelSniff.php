<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ResourceModelSniff
 * Detects data access code outside of ResourceModel.
 */
class ResourceModelSniff extends \MEQP1\Sniffs\Classes\ResourceModelSniff
{
    /**
     * Substring Namespace name
     *
     * @var string
     */
    protected $resourceModel = "\\Model\\ResourceModel";

    /**
     * Token to search
     *
     * @var int
     */
    protected $token = T_NAMESPACE;

    /**
     * Check if class in Resource Model
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @return mixed
     */
    protected function isInResourceModel(PHP_CodeSniffer_File $phpcsFile)
    {
        $namespaceName = $this->getNamespaceName($phpcsFile);
        return $this->isInResourceModelFlag($namespaceName);
    }

    /**
     * Get namespace name for class
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @return string
     */
    private function getNamespaceName(PHP_CodeSniffer_File $phpcsFile)
    {
        $neededPointer = $this->getNeededPointer($phpcsFile);
        $namespaceNamePointer = $phpcsFile->findNext(T_STRING, $neededPointer + 1);

        $namespaceName = $phpcsFile->getTokens()[$namespaceNamePointer]['content'];
        $semiColon = $phpcsFile->findNext(T_SEMICOLON, $namespaceNamePointer + 1);
        $next = $phpcsFile->findNext([T_NS_SEPARATOR], $namespaceNamePointer + 1);
        while ($next != $semiColon) {
            $namespaceName .= $phpcsFile->getTokens()[$next]['content'];
            $namespaceNamePointer = $next;
            $next = $phpcsFile->findNext([T_STRING, T_NS_SEPARATOR, T_SEMICOLON], $namespaceNamePointer + 1);
        }
        return $namespaceName;
    }
}
