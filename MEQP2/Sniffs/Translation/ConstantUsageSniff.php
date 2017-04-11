<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Translation;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ConstantUsageSniff.
 * Make sure that constants are not used as the first argument of translation function.
 */
class ConstantUsageSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * If true, comments will be ignored if they are found in the code.
     *
     * @var bool
     */
    public $ignoreComments = true;

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
    // @codingStandardsIgnoreLine
    protected $warningMessage = 'Constants are not allowed as the first argument of translation function, use string literal instead.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'VariableTranslation';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_DOUBLE_COLON];
    }

    /**
     * @inheritdoc
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $doubleColon = $tokens[$stackPtr];
        $currentLine = $doubleColon['line'];
        $lineContent = $this->getLineContent($phpcsFile, $currentLine);
        $previousLineContent = $this->getLineContent($phpcsFile, $currentLine - 1);
        $previousLineRegexp = '~__\($|Phrase\($~';
        $currentLineRegexp = '~__\(.+\)|Phrase\(.+\)~';
        $currentLineMatch = preg_match($currentLineRegexp, $lineContent) !== 0;
        $previousLineMatch = preg_match($previousLineRegexp, $previousLineContent) !== 0;
        $this->previousLineContent = $lineContent;
        $constantRegexp = '[^\'"]+::[A-Z_0-9]+.*';
        if ($currentLineMatch) {
            $variableRegexp = "~__\({$constantRegexp}\)|Phrase\({$constantRegexp}\)~";
            if (preg_match($variableRegexp, $lineContent) !== 0) {
                $phpcsFile->addWarning(
                    $this->warningMessage,
                    $this->getFirstLineToken($phpcsFile, $currentLine),
                    $this->warningCode,
                    [],
                    $this->severity
                );
            }
        } else {
            if ($previousLineMatch) {
                $variableRegexp = "~^\s+{$constantRegexp}~";
                if (preg_match($variableRegexp, $lineContent) !== 0) {
                    $phpcsFile->addWarning(
                        $this->warningMessage,
                        $this->getFirstLineToken($phpcsFile, $currentLine - 1),
                        $this->warningCode,
                        [],
                        $this->severity
                    );
                }
            }
        }
    }

    /**
     * Get line content by it's line number.
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $line
     * @return string
     */
    private function getLineContent(\PHP_CodeSniffer_File $phpcsFile, $line)
    {
        $tokens = $phpcsFile->getTokens();
        return implode('', array_column(array_filter($tokens, function ($item) use ($line) {
            return $item['line'] == $line;
        }), 'content'));
    }

    /**
     * Get index of first token in line.
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $line
     * @return int
     */
    private function getFirstLineToken(\PHP_CodeSniffer_File $phpcsFile, $line)
    {
        $tokens = $phpcsFile->getTokens();
        return array_keys(array_filter($tokens, function ($item) use ($line) {
            return $item['line'] == $line;
        }))[0];
    }
}
