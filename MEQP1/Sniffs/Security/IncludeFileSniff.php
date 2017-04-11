<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Security;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;

/**
 * Class IncludeFileSniff
 * Detects possible improper usage of include functions.
 */
class IncludeFileSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundIncludeFile';

    /**
     * Pattern to match urls.
     *
     * @var string
     */
    protected $urlPattern = '#(https?|ftp)://.*#i';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$includeTokens;
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $firstToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr + 1, null, true);
        $message = '"%s" statement detected. File manipulations are discouraged.';
        if ($tokens[$firstToken]['code'] === T_OPEN_PARENTHESIS) {
            $message .= ' Statement is not a function, no parentheses are required.';
            $firstToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $firstToken + 1, null, true);
        }
        $nextToken = $firstToken;
        $ignoredTokens = array_merge(PHP_CodeSniffer_Tokens::$emptyTokens, [T_CLOSE_PARENTHESIS]);
        $isConcatenated = false;
        $isUrl = false;
        $hasVariable = false;
        $includePath = '';
        while ($tokens[$nextToken]['code'] !== T_SEMICOLON &&
            $tokens[$nextToken]['code'] !== T_CLOSE_TAG) {
            switch ($tokens[$nextToken]['code']) {
                case T_CONSTANT_ENCAPSED_STRING:
                    $includePath = trim($tokens[$nextToken]['content'], '"\'');
                    if (preg_match($this->urlPattern, $includePath)) {
                        $isUrl = true;
                    }
                    break;
                case T_STRING_CONCAT:
                    $isConcatenated = true;
                    break;
                case T_VARIABLE:
                    $hasVariable = true;
                    break;
            }
            $nextToken = $phpcsFile->findNext($ignoredTokens, $nextToken + 1, null, true);
        }
        if ($tokens[$stackPtr]['level'] === 0 && stripos($includePath, 'controller') !== false) {
            $nextToken = $phpcsFile->findNext(T_CLASS, $nextToken + 1);
            if ($nextToken) {
                $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $nextToken + 1, null, true);
                $className = $tokens[$nextToken]['content'];
                if (strripos($className, 'controller') !== false) {
                    return;
                }
            }
        }
        if ($isUrl) {
            $message .= ' Passing urls is forbidden.';
        }
        if ($isConcatenated) {
            $message .= ' Concatenating is forbidden.';
        }
        if ($hasVariable) {
            $message .= ' Variables inside are insecure.';
        }
        $phpcsFile->addWarning(
            $message,
            $stackPtr,
            $this->warningCode,
            [$tokens[$stackPtr]['content']],
            $this->severity
        );
    }
}
