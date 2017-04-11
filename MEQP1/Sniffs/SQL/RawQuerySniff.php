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
 * Class RawQuerySniff
 * Detects possible raw SQL queries.
 */
class RawQuerySniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'Possible raw SQL statement %s detected.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundRawSql';

    /**
     * List of SQL statements.
     *
     * @var array
     */
    protected $statements = [
        'SELECT',
        'UPDATE',
        'INSERT',
        'CREATE',
        'DELETE',
        'ALTER',
        'DROP',
        'TRUNCATE'
    ];

    /**
     * List of query functions.
     *
     * @var array
     */
    protected $queryFunctions = [
        'query',
        'raw_query',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return array_merge(PHP_CodeSniffer_Tokens::$stringTokens, [T_HEREDOC, T_NOWDOC]);
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $ignoredTokens = array_merge([T_WHITESPACE, T_OPEN_PARENTHESIS], PHP_CodeSniffer_Tokens::$stringTokens);
        $prev = $tokens[$phpcsFile->findPrevious($ignoredTokens, $stackPtr - 1, null, true)];

        if ($prev['code'] === T_EQUAL
            || ($prev['code'] === T_STRING && in_array($prev['content'], $this->queryFunctions))
            || in_array($tokens[$stackPtr]['code'], [T_HEREDOC, T_NOWDOC])
        ) {
            $trim = function ($str) {
                return trim(str_replace(['\'', '"'], '', $str));
            };
            if (preg_match('/^(' . implode('|', $this->statements) . ')\s/i', $trim($tokens[$stackPtr]['content']))) {
                $phpcsFile->addWarning(
                    $this->warningMessage,
                    $stackPtr,
                    $this->warningCode,
                    [trim($tokens[$stackPtr]['content'])],
                    $this->severity
                );
            }
        }
    }
}
