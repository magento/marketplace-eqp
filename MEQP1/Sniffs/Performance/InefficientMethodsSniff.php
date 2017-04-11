<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Performance;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class InefficientMethodsSniff
 * Detects use of inefficient methods.
 */
class InefficientMethodsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 8;

    /**
     * Mapping for function's code and message.
     *
     * @var array
     */
    protected $map = [
        'getfirstitem' => [
            'message' => '%s does not limit the result of collection load to one item.',
            'code' => 'FoundGetFirstItem'
        ],
        'fetchall' => [
            'message' => '%s can be memory inefficient for large data sets.',
            'code' => 'FoundFetchAll'
        ],
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_OBJECT_OPERATOR, T_DOUBLE_COLON];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $posOfMethod = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        $methodName = strtolower($tokens[$posOfMethod]['content']);

        if (array_key_exists($methodName, $this->map)) {
            $code = $this->map[$methodName]['code'];
            $warningMessage = sprintf($this->map[$methodName]['message'], $tokens[$posOfMethod]['content']);

            $phpcsFile->addWarning(
                $warningMessage,
                $posOfMethod,
                $code,
                [$tokens[$posOfMethod]['content'] . '()'],
                $this->severity
            );
        }
    }
}
