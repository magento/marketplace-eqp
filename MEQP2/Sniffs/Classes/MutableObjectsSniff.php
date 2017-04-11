<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class MutableObjectsSniff
 * Detects if mutable objects are used in __constructor
 */
class MutableObjectsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 6;

    /**
     * 'as' keyword in usages.
     *
     * @var string
     */
    protected $asKeyword = ' as ';

    /**
     * __construct() method.
     *
     * @var string
     */
    protected $constructMethod = '__construct';

    /**
     * Warning message.
     *
     * @var string
     */
    // @codingStandardsIgnoreLine
    protected $warningMessage = '%s object MUST NOT be requested in constructor. It can only be passed as a method argument.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'MutableObjects';

    /**
     * Forbidden words.
     *
     * @var array
     */
    protected $forbiddenWords = [
        'Cookie',
        'Request\\\\Http',
        'Session',
        'Request'
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_CLASS];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $sourceFile, $index)
    {
        $tokens = $sourceFile->getTokens();
        $functionPosition = $this->getFunctionPosition($sourceFile, $index);
        if ($functionPosition !== false) {
            $constructorDependencies = $this->getAvailableTypeHints(
                $sourceFile->getMethodParameters($functionPosition)
            );
            $slicedArrayConstructorParams = array_slice(
                $tokens,
                $tokens[$functionPosition]['parenthesis_opener'],
                ($tokens[$functionPosition]['parenthesis_closer'] - $tokens[$functionPosition]['parenthesis_opener'])
                + 1,
                true
            );
            //T_FUNCTION = __construct()
            $this->processDependencies($sourceFile, $constructorDependencies, $slicedArrayConstructorParams);
            //T_USE
            $slicedArrayNamespaces = array_slice(
                $tokens,
                0,
                $index + 1
            );
            $this->processUse(
                $sourceFile,
                $index,
                $constructorDependencies,
                $slicedArrayNamespaces,
                $slicedArrayConstructorParams
            );
        }
    }

    /**
     * Process namespaces.
     *
     * @param PHP_CodeSniffer_File $sourceFile
     * @param int $index
     * @param array $constructorDependencies
     * @param array $slicedArrayNamespaces
     * @param array $slicedArrayConstructorParams
     * @return void
     */
    private function processUse(
        PHP_CodeSniffer_File $sourceFile,
        $index,
        $constructorDependencies,
        $slicedArrayNamespaces,
        $slicedArrayConstructorParams
    ) {
    

        foreach ($slicedArrayNamespaces as $indexToken => $token) {
            if ($indexToken < $index && $token['code'] === T_USE) {
                $this->processUseSingle(
                    $sourceFile,
                    $indexToken,
                    $constructorDependencies,
                    $slicedArrayConstructorParams
                );
            }
        }
    }

    /**
     * Process constructor dependencies.
     *
     * @param PHP_CodeSniffer_File $sourceFile
     * @param array $constructorDependencies
     * @param array $slicedArrayConstructorParams
     * @return void
     */
    private function processDependencies(
        PHP_CodeSniffer_File $sourceFile,
        $constructorDependencies,
        $slicedArrayConstructorParams
    ) {
    

        foreach ($constructorDependencies as $variable => $dependency) {
            if ($matches = $this->constantsForbidden($dependency)) {
                $index = $this->findIndexByTagContent($variable, $slicedArrayConstructorParams);
                $this->processWarning($sourceFile, $index, $matches[1]);
            }
        }
    }

    /**
     * Get function position.
     *
     * @param PHP_CodeSniffer_File $sourceFile
     * @param int $index
     * @return mixed
     */
    private function getFunctionPosition(PHP_CodeSniffer_File $sourceFile, $index)
    {
        $functionPosition = $sourceFile->findNext(T_FUNCTION, $index);
        while ($functionPosition !== false &&
            strtolower($sourceFile->getDeclarationName($functionPosition)) !== $this->constructMethod) {
            $functionPosition = $sourceFile->findNext(T_FUNCTION, $functionPosition + 1);
        }
        return $functionPosition;
    }

    /**
     * Process "use" namespaces.
     *
     * @param PHP_CodeSniffer_File $sourceFile
     * @param string $index
     * @param array $constructorDependencies
     * @param array $slicedArrayConstructorParams
     * @return void
     */
    private function processUseSingle(
        PHP_CodeSniffer_File $sourceFile,
        $index,
        $constructorDependencies,
        $slicedArrayConstructorParams
    ) {
    

        $source = '';
        $nextUse = $sourceFile->findNext(T_SEMICOLON, $index);
        for ($i = $sourceFile->findNext(T_STRING, $index); $i <= $nextUse - 1; $i++) {
            $source .= $sourceFile->getTokens()[$i]['content'];
        }
        $usages = explode(',', $source);
        foreach ($usages as $usage) {
            $lowerUsage = strtolower($usage);
            $asPosition = strpos($lowerUsage, $this->asKeyword);
            if ($asPosition !== false) {
                $matches = [];
                $pattern = '/.*(?i)as(.*)/';
                preg_match($pattern, $usage, $matches);
                $as = trim($matches[1]);
                /**
                 * IF use like
                 * use \Magento\Customer\Model\Session as Customer;
                 */
                if ($matches = $this->constantsForbidden($usage)) {
                    // FIND in constructor and add as an error
                    array_filter($constructorDependencies, function ($dependency) use (
                        $as,
                        $sourceFile,
                        $matches,
                        $slicedArrayConstructorParams
                    ) {
                        if (trim($as) === $dependency) {
                            $index = $this->findIndexByTagContent($as, $slicedArrayConstructorParams);
                            $this->processWarning($sourceFile, $index, $matches[1]);
                        }
                    });
                }
            }
        }
    }

    /**
     * Add warning.
     *
     * @param PHP_CodeSniffer_File $sourceFile
     * @param int $index
     * @param string $replacement
     * @return void
     */
    private function processWarning(PHP_CodeSniffer_File $sourceFile, $index, $replacement)
    {
        $sourceFile->addWarning(
            $this->warningMessage,
            $index,
            $this->warningCode,
            [$replacement],
            $this->severity
        );
    }

    /**
     * Find an index of a tag by its content.
     *
     * @param string $content
     * @param array $slicedArrayConstructorParams
     * @return mixed
     */
    private function findIndexByTagContent($content, $slicedArrayConstructorParams)
    {
        foreach ($slicedArrayConstructorParams as $key => $value) {
            if ($value['content'] == trim($content)) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Get list of type hints present in __construct() method.
     *
     * @param array $arguments
     * @return array
     */
    private function getAvailableTypeHints(array $arguments)
    {
        $data = [];
        foreach ($arguments as $argument) {
            if ($argument['type_hint'] !== null) {
                $data[$argument['name']] = $argument['type_hint'];
            }
        }
        return $data;
    }

    /**
     * Check forbidden.
     *
     * @param string $string
     * @return array
     */
    private function constantsForbidden($string)
    {
        $matches = [];
        $forbidden = implode('|', $this->forbiddenWords);
        $pattern = "/.*\\\\($forbidden)([\\s;]|$)/";
        preg_match($pattern, $string, $matches);
        return $matches;
    }
}
