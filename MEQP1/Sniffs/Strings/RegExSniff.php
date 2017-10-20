<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Strings;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Class RegExSniff
 * Detects executable regular expressions.
 */
class RegExSniff implements Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 10;

    /**
     * String representation of error.
     *
     * @var string
     */
    // @codingStandardsIgnoreLine
    protected $errorMessage = "Possible executable regular expression in %s. Make sure that the pattern doesn't contain 'e' modifier";

    /**
     * Error violation code.
     *
     * @var string
     */
    protected $errorCode = 'PossibleExecutableRegEx';

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
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (!in_array($tokens[$stackPtr]['content'], $this->functions)) {
            return;
        }
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if (in_array($tokens[$prevToken]['code'], $this->ignoreTokens)) {
            return;
        }
        $nextToken = $phpcsFile->findNext([T_WHITESPACE, T_OPEN_PARENTHESIS], $stackPtr + 1, null, true);
        if (in_array($tokens[$nextToken]['code'], Tokens::$stringTokens)
            && preg_match('/[#\/|~\}\)][imsxADSUXJu]*e[imsxADSUXJu]*.$/', $tokens[$nextToken]['content'])
        ) {
            $phpcsFile->addError(
                $this->errorMessage,
                $stackPtr,
                $this->errorCode,
                [$tokens[$stackPtr]['content']],
                $this->severity
            );
        }
    }
}
