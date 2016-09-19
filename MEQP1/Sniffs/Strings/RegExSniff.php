<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Strings;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;

/**
 * Class RegExSniff
 * Detects possible executable regular expressions.
 */
class RegExSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     */
    // @codingStandardsIgnoreStart
    protected $warningMessage = "Possible executable regular expression in %s. Make sure that the pattern doesn't contain 'e' modifier";
    // @codingStandardsIgnoreEnd

    /**
     * Warning violation code.
     */
    protected $warningCode = 'PossibleExecutableRegEx';

    /**
     * Observed functions.
     *
     * @var array
     */
    protected $functions = ['preg_replace'];

    /**
     * List of ignored tokens.
     *
     * @var array
     */
    protected $ignoreTokens = [
        T_DOUBLE_COLON,
        T_OBJECT_OPERATOR,
        T_FUNCTION,
        T_CONST,
        T_CLASS,
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (!in_array($tokens[$stackPtr]['content'], $this->functions)) {
            return;
        }

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $this->ignoreTokens)) {
            return;
        }

        $nextToken = $phpcsFile->findNext([T_WHITESPACE, T_OPEN_PARENTHESIS], ($stackPtr + 1), null, true);
        if (in_array($tokens[$nextToken]['code'], PHP_CodeSniffer_Tokens::$stringTokens)
            && preg_match('/[#\/|~\}\)][imsxADSUXJu]*e[imsxADSUXJu]*.$/', $tokens[$nextToken]['content'])
        ) {
            $phpcsFile->addWarning(
                $this->warningMessage,
                $stackPtr,
                $this->warningCode,
                [$tokens[$stackPtr]['content']]
            );
        }
    }
}
