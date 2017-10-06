<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\SQL;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use MEQP\Utils\Helper;

/**
 * Class MissedIndexesSniff
 * Detects possible missed indexes in install and update schema classes.
 */
class MissedIndexesSniff implements Sniff
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
     * String representation of error.
     *
     * @var string
     */
    protected $warningMessage = 'There was not found any index in database schema file.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'MissedIndexes';

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_OPEN_TAG];
    }

    /**
     * @inheritdoc
     */
    public function process(File $sourceFile, $index)
    {
        if (strpos($sourceFile->getFilename(), 'sql') !== false) {
            $methods = $this->getCalledMethods($sourceFile);
            $methodNames = array_map(function ($element) {
                return $element['content'];
            }, $methods);
            if (in_array('newTable', $methodNames) && !in_array('addIndex', $methodNames)) {
                $sourceFile->addWarning($this->warningMessage, $index, $this->warningCode, [], $this->severity);
            }
        }
    }
}
