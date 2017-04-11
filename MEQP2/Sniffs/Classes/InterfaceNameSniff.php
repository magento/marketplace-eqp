<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class InterfaceNameSniff
 * Detects possible interface declaration without 'Interface' suffix.
 */
class InterfaceNameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 6;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = 'Interface should have name that ends with "Interface" suffix.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'WrongInterfaceName';

    /**
     * Interface suffix.
     *
     * @var string
     */
    protected $interfaceSuffix = 'Interface';


    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_INTERFACE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $sourceFile, $stackPtr)
    {
        $tokens = $sourceFile->getTokens();
        $declarationLine = $tokens[$stackPtr]['line'];
        $suffixLength = strlen($this->interfaceSuffix);
        // Find first T_STRING after 'interface' keyword in the line and verify it
        while ($tokens[$stackPtr]['line'] === $declarationLine) {
            if ($tokens[$stackPtr]['type'] === 'T_STRING') {
                if (substr($tokens[$stackPtr]['content'], 0 - $suffixLength) !== $this->interfaceSuffix) {
                    $sourceFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
                }
                break;
            }
            $stackPtr++;
        }
    }
}
