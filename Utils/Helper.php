<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Utils;

/**
 * Trait Helper
 * Helper trait for PHP_CodeSniffer_File processing.
 */
trait Helper
{
    /**
     * Get list of all called methods.
     *
     * @param \PHP_CodeSniffer_File $file
     * @param int $startIndex
     * @return array
     */
    public function getCalledMethods(\PHP_CodeSniffer_File $file, $startIndex = 0)
    {
        $methods = [];
        $tokens = $file->getTokens();
        $startIndex = $file->findNext(T_STRING, $startIndex);
        while ($startIndex !== false) {
            $prevIndex = $file->findPrevious([T_WHITESPACE], $startIndex - 1, null, true);
            $prevCode = $tokens[$prevIndex]['code'];
            $nextIndex = $file->findNext([T_WHITESPACE], $startIndex + 1, null, true);
            $nextCode = $tokens[$nextIndex]['code'];
            if (($prevCode == T_OBJECT_OPERATOR || $prevCode == T_DOUBLE_COLON)
                && $nextCode == T_OPEN_PARENTHESIS
            ) {
                $methods[$startIndex] = $tokens[$startIndex];
            }
            $startIndex = $file->findNext(T_STRING, $startIndex + 1);
        }
        return $methods;
    }

    /**
     * Get Utils directory path.
     *
     * @return string
     */
    public function getUtilsDir()
    {
        return __DIR__;
    }

    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /**
     * Get object manager instance.
     *
     * @return \Magento\Framework\ObjectManagerInterface
     * @throws \Exception
     */
    public function getObjectManager()
    {
        return $this->getBootstrap()->getObjectManager();
    }

    /**
     * Returns a bootstrap of Magento application.
     *
     * @return \Magento\Framework\App\Bootstrap
     */
    public function getBootstrap()
    {
        $m2path = \PHP_CodeSniffer::getConfigData('m2-path');
        if (!file_exists($m2path . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
            die('Wrong value specified for m2-path.' . PHP_EOL);
        }
        require $m2path . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
        return \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
    }
}
