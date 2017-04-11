<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use \Utils\Helper;

/**
 * Class NameResolutionSniff
 * Dynamic sniff that detects the use of literal class and interface names.
 * Requires 'm2-path' to be configured.
 */
class NameResolutionSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Include Helper trait.
     */
    use Helper;

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
    protected $warningMessage = 'Literal namespace detected. Use ::class notation instead.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'LiteralNamespaceFound';

    /**
     * Literal namespace pattern.
     *
     * @var string
     */
    private $literalNamespacePattern = '/^[\\\]{0,2}[A-Z][A-Za-z0-9]+([\\\]{1,2}[A-Z][A-Za-z0-9]+){2,}(?!\\\+)$/';

    /**
     * Class names from current file.
     *
     * @var array
     */
    private $classNames = [];

    /**
     * A bootstrap of Magento application.
     *
     * @var \Magento\Framework\App\Bootstrap
     */
    private $bootstrap;

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_CONSTANT_ENCAPSED_STRING,
            T_DOUBLE_QUOTED_STRING,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $sourceFile, $stackPtr)
    {
        if (\PHP_CodeSniffer::getConfigData('m2-path') === null ||
            $sourceFile->findPrevious([T_STRING_CONCAT, T_CONCAT_EQUAL], $stackPtr - 1, null, false, null, true) ||
            $sourceFile->findNext([T_STRING_CONCAT, T_CONCAT_EQUAL], $stackPtr + 1, null, false, null, true)
        ) {
            return;
        }
        $tokens = $sourceFile->getTokens();
        $content = trim($tokens[$stackPtr]['content'], "\"'");
        if (preg_match($this->literalNamespacePattern, $content) === 1) {
            if (!$this->bootstrap) {
                $this->bootstrap = $this->getBootstrap();
            }
            if ($this->classExists($content)) {
                $sourceFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
            }
        }
    }

    /**
     * Checks if class exists by class name.
     *
     * @param string $className
     * @return bool
     */
    private function classExists($className)
    {
        if (!array_key_exists($className, $this->classNames)) {
            $this->classNames[$className] = class_exists($className) || interface_exists($className);
        }
        return $this->classNames[$className];
    }
}
