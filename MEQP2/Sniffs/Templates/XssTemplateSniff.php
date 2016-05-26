<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Templates;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class XssTemplateSniff
 * Detects not escaped output in phtml templates.
 */
class XssTemplateSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = 'Unescaped output detected.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'FoundUnescaped';

    /**
     * Magento escape methods.
     *
     * @var array
     */
    protected $allowedMethods = [
        'escapeUrl',
        'escapeJsQuote',
        'escapeQuote',
        'escapeXssInUrl',
    ];

    /**
     * Allowed method name - {suffix}Html{postfix}()
     *
     * @var string
     */
    protected $containMethodName = 'html';

    /**
     * PHP functions, that no need escaping.
     *
     * @var array
     */
    protected $allowedFunctions = ['count'];

    /**
     * Parsed statements to check for escaping.
     *
     * @var array
     */
    private $statements = [];

    /**
     * PHP_CodeSniffer file.
     *
     * @var PHP_CodeSniffer_File
     */
    private $file;

    /**
     * All tokens from current file.
     *
     * @var array
     */
    private $tokens;

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_ECHO,
            T_OPEN_TAG_WITH_ECHO,
            T_PRINT,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->file = $phpcsFile;
        $this->tokens = $this->file->getTokens();

        if ($this->findNoEscapeComment($stackPtr)) {
            return;
        }

        $endOfStatement = $phpcsFile->findNext([T_CLOSE_TAG, T_SEMICOLON], $stackPtr);

        $this->parseLineStatement($stackPtr + 1, $endOfStatement);

        while ($this->statements) {
            $statement = array_shift($this->statements);
            $this->detectUnescapedString($statement);
        }
    }

    /**
     * If @noEscape is before output, it doesn't require escaping.
     *
     * @param int $stackPtr
     * @return bool
     */
    private function findNoEscapeComment($stackPtr)
    {
        if ($this->tokens[$stackPtr]['code'] === T_ECHO) {
            $startOfStatement = $this->file->findPrevious(T_OPEN_TAG, $stackPtr);
            $noEscapeComment = $this->file->findPrevious(T_COMMENT, $stackPtr, $startOfStatement);
            if ($noEscapeComment !== false
                && strpos($this->tokens[$noEscapeComment]['content'], '@noEscape') !== false
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Find unescaped statement by following rules:
     * http://devdocs.magento.com/guides/v2.0/frontend-dev-guide/templates/template-security.html
     *
     * @param array $statement
     * @return void
     */
    private function detectUnescapedString($statement)
    {
        $posOfFirstElement = $this->file->findNext(T_WHITESPACE, $statement['start'], $statement['end'], true);
        $posOfArithmeticOperator = $this->file->findNext(
            [T_PLUS, T_MINUS, T_DIVIDE, T_MULTIPLY, T_MODULUS, T_POW],
            $statement['start'],
            $statement['end']
        );
        if ($posOfArithmeticOperator !== false) {
            return;
        }
        switch ($this->tokens[$posOfFirstElement]['code']) {
            case T_OPEN_PARENTHESIS:
                $this->addStatement($posOfFirstElement + 1, $this->tokens[$posOfFirstElement]['parenthesis_closer']);
                break;
            case T_STRING:
                if (!in_array($this->tokens[$posOfFirstElement]['content'], $this->allowedFunctions)) {
                    $this->file->addWarning($this->warningMessage, $posOfFirstElement, $this->warningCode);
                }
                break;
            case T_START_HEREDOC:
            case T_DOUBLE_QUOTED_STRING:
                $this->file->addWarning($this->warningMessage, $posOfFirstElement, $this->warningCode);
                break;
            case T_VARIABLE:
                $posOfObjOperator = $this->findLastInScope(T_OBJECT_OPERATOR, $posOfFirstElement, $statement['end']);
                if ($posOfObjOperator === false) {
                    $this->file->addWarning($this->warningMessage, $posOfFirstElement, $this->warningCode);
                    break;
                }
                $posOfMethod = $this->file->findNext([T_STRING, T_VARIABLE], $posOfObjOperator + 1, $statement['end']);
                if ($this->tokens[$posOfMethod]['code'] === T_STRING &&
                    (in_array($this->tokens[$posOfMethod]['content'], $this->allowedMethods) ||
                        stripos($this->tokens[$posOfMethod]['content'], $this->containMethodName) !== false)
                ) {
                    break;
                } else {
                    $this->file->addWarning($this->warningMessage, $posOfMethod, $this->warningCode);
                }
                break;
            case T_CONSTANT_ENCAPSED_STRING:
            case T_DOUBLE_CAST:
            case T_INT_CAST:
            case T_BOOL_CAST:
            default:
                return;
        }
    }

    /**
     * Split line from start to end by ternary operators and concatenations.
     *
     * @param int $start
     * @param int $end
     * @return void
     */
    private function parseLineStatement($start, $end)
    {
        $posOfLastInlineThen = $this->findLastInScope(T_INLINE_THEN, $start, $end);
        if ($posOfLastInlineThen !== false) {
            $posOfInlineElse = $this->file->findNext(T_INLINE_ELSE, $posOfLastInlineThen, $end);
            $this->addStatement($posOfLastInlineThen + 1, $posOfInlineElse);
            $this->addStatement($posOfInlineElse + 1, $end);
        } else {
            do {
                $posOfConcat = $this->findNextInScope(T_STRING_CONCAT, $start, $end);
                if ($posOfConcat !== false) {
                    $this->addStatement($start, $posOfConcat);
                } else {
                    $this->addStatement($start, $end);
                }
                $start = $posOfConcat + 1;
            } while ($posOfConcat !== false);
        }
    }

    /**
     * Push statement range in queue to check.
     *
     * @param $start
     * @param $end
     * @return void
     */
    private function addStatement($start, $end)
    {
        $this->statements[] = [
            'start' => $start,
            'end' => $end
        ];
    }

    /**
     * Finds next token position in current scope.
     *
     * @param $types
     * @param $start
     * @param $end
     * @return int|bool
     */
    private function findNextInScope($types, $start, $end)
    {
        $types = (array)$types;
        $next = $this->file->findNext(array_merge($types, [T_OPEN_PARENTHESIS]), $start, $end);
        $nextToken = $this->tokens[$next];
        if ($nextToken['code'] === T_OPEN_PARENTHESIS) {
            return $this->findNextInScope($types, $nextToken['parenthesis_closer'] + 1, $end);
        } else {
            return $next;
        }
    }

    /**
     * Finds last token position in current scope.
     *
     * @param $types
     * @param $start
     * @param $end
     * @param bool|int $last
     * @return int|bool
     */
    private function findLastInScope($types, $start, $end, $last = false)
    {
        $types = (array)$types;
        $nextInScope = $this->findNextInScope($types, $start, $end);
        if ($nextInScope !== false && $nextInScope > $last) {
            return $this->findLastInScope($types, $nextInScope + 1, $end, $nextInScope);
        } else {
            return $last;
        }
    }
}
