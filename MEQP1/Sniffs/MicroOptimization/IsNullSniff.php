<?php

namespace MEQP1\Sniffs\MicroOptimization;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

class IsNullSniff implements PHP_CodeSniffer_Sniff
{
    protected $blacklist = 'is_null';

    public function register()
    {
        return [T_STRING];
    }

    public function process(PHP_CodeSniffer_File $sourceFile, $stackPtr)
    {
        $tokens = $sourceFile->getTokens();
        if ($tokens[$stackPtr]['content'] === $this->blacklist) {
            $sourceFile->addError("is_null must be avoided. Use strict comparison instead.", $stackPtr);
        }
    }
}
