<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP\Utils;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;

/**
 * Trait Helper
 * Helper trait for PHP_CodeSniffer_File processing.
 */
trait Helper
{
    /**
     * Get list of all called methods.
     *
     * @param File $file
     * @param int $startIndex
     * @return array
     */
    public function getCalledMethods(File $file, $startIndex = 0)
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
}
