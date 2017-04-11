<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class ObjectManagerSniff
 * Detects direct ObjectManager usage.
 */
class ObjectManagerSniff implements PHP_CodeSniffer_Sniff
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
    // @codingStandardsIgnoreLine
    protected $warningMessage = 'The direct use of ObjectManager is discouraged. Inject necessary dependencies via constructor.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'ObjectManagerFound';

    /**
     * Possible names of ObjectManager variable or methods from where it can be called.
     *
     * @var array
     */
    protected $objectManagerNames = [
        'om',
        '_om',
        'objectmanager',
        '_objectmanager',
        'getobjectmanager',
    ];

    /**
     * ObjectManager methods we are looking for.
     *
     * @var array
     */
    protected $objectManagerMethods = [
        'get',
        'create',
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
        $methodPosition = $phpcsFile->findNext(T_STRING, $stackPtr + 1);
        if ($methodPosition !== false &&
            in_array($tokens[$methodPosition]['content'], $this->objectManagerMethods)
        ) {
            $objectManagerPosition = $phpcsFile->findPrevious([T_STRING, T_VARIABLE], $stackPtr - 1);
            if ($objectManagerPosition !== false) {
                $objectManagerName = strtolower($tokens[$objectManagerPosition]['content']);
                if ($tokens[$objectManagerPosition]['code'] === T_VARIABLE) {
                    $objectManagerName = substr($objectManagerName, 1);
                }
                if (in_array($objectManagerName, $this->objectManagerNames)) {
                    $phpcsFile->addWarning($this->warningMessage, $stackPtr, $this->warningCode, [], $this->severity);
                }
            }
        }
    }
}
