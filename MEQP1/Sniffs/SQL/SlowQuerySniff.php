<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\SQL;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;

/**
 * Class SlowQuerySniff
 * Detects possible slow SQL queries.
 */
class SlowQuerySniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = 'Possible slow SQL method %s detected.';

    /**
     * Slow SQL violation code.
     *
     * @var string
     */
    protected $slowSqlCode = 'FoundSlowSql';

    /**
     * Slow raw SQL violation code.
     *
     * @var string
     */
    protected $slowRawSqlCode = 'FoundSlowRawSql';

    /**
     * List of slow adapter methods.
     *
     * @var array
     */
    protected $adapterMethods = [
        'group',
        'having',
        'distinct',
        'addLikeEscape',
        'escapeLikeValue',
        'union',
        'orHaving',
    ];

    /**
     * List of slow SQL queries.
     *
     * @var array
     */
    protected $rawStatements = [
        'GROUP BY',
        'HAVING',
        'DISTINCT',
        'LIKE',
        'UNION',
    ];

    /**
     * Get list of string tokens.
     *
     * @return array
     */
    protected function getStrTokens()
    {
        return array_merge(PHP_CodeSniffer_Tokens::$stringTokens, [T_HEREDOC, T_NOWDOC]);
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        return array_merge([T_STRING], $this->getStrTokens());
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $ignoredTokens = array_merge([T_WHITESPACE, T_OPEN_PARENTHESIS], PHP_CodeSniffer_Tokens::$stringTokens);
        $prev = $tokens[$phpcsFile->findPrevious($ignoredTokens, $stackPtr - 1, null, true)];
        if (($prev['code'] === T_EQUAL || $prev['code'] == T_STRING)
            && in_array($tokens[$stackPtr]['code'], $this->getStrTokens())
        ) {
            if (preg_match('/(' . implode('|', $this->rawStatements) . ')\s/i', trim($tokens[$stackPtr]['content']))) {
                $phpcsFile->addWarning(
                    $this->warningMessage,
                    $stackPtr,
                    $this->slowRawSqlCode,
                    [trim($tokens[$stackPtr]['content'])],
                    $this->severity
                );
            }
        } else {
            if ($prev['code'] === T_OBJECT_OPERATOR
                && $tokens[$stackPtr]['code'] === T_STRING
                && in_array($tokens[$stackPtr]['content'], $this->adapterMethods)
            ) {
                $phpcsFile->addWarning(
                    $this->warningMessage,
                    $stackPtr,
                    $this->slowSqlCode,
                    [trim($tokens[$stackPtr]['content'])],
                    $this->severity
                );
            }
        }
    }
}
