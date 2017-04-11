<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\SQL;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;
use Utils\Helper;

/**
 * Class MissedIndexesSniff
 * Detects possible missed indexes in install and update schema classes.
 */
class MissedIndexesSniff implements PHP_CodeSniffer_Sniff
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
     * Install/update schema classes.
     *
     * @var array
     */
    protected $schemaClasses = [
        'InstallSchema',
        'UpgradeSchema',
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
        if (in_array($sourceFile->getDeclarationName($index), $this->schemaClasses)) {
            $methods = $this->getCalledMethods($sourceFile);
            $methodNames = array_column($methods, 'content');
            if (in_array('newTable', $methodNames) && !in_array('addIndex', $methodNames)) {
                $sourceFile->addWarning($this->warningMessage, $index, $this->warningCode, [], $this->severity);
            }
        }
    }
}
