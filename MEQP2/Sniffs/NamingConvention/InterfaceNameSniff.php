<?php
namespace MEQP2\Sniffs\NamingConvention;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

class InterfaceNameSniff implements PHP_CodeSniffer_Sniff
{
    const INTERFACE_SUFFIX = 'Interface';

    public function register()
    {
        return [T_INTERFACE];
    }

    public function process(PHP_CodeSniffer_File $sourceFile, $stackPtr)
    {
        $tokens = $sourceFile->getTokens();
        $declarationLine = $tokens[$stackPtr]['line'];
        $suffixLength = strlen(self::INTERFACE_SUFFIX);
        // Find first T_STRING after 'interface' keyword in the line and verify it
        while ($tokens[$stackPtr]['line'] == $declarationLine) {
            if ($tokens[$stackPtr]['type'] == 'T_STRING') {
                if (substr($tokens[$stackPtr]['content'], 0 - $suffixLength) !== self::INTERFACE_SUFFIX) {
                    $sourceFile->addError('Interface should have name that ends with "Interface" suffix.', $stackPtr);
                }
                break;
            }
            $stackPtr++;
        }
    }
}
