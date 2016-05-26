<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use Utils\Helper;

/**
 * Class ResourceModelSniff
 * Detects data access code outside of ResourceModel.
 */
class ResourceModelSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Include Helper trait
     */
    use Helper;
    
    /**
     * String representation of warning.
     */
    protected $warningMessage = 'Data access method %s detected outside of Resource Model';

    /**
     * Warning violation code.
     */
    protected $warningCode = 'OutsideOfResourceModel';

    /**
     * Substring Class name
     */
    protected $resourceModel = 'Model_Resource';

    /**
     * Token to search
     */
    protected $token = T_CLASS;

    /**
     * List of methods which is allowed only in Resource Models classes.
     *
     * @var array
     */
    protected $disallowedMethods = [
        'select',
        'reset',
        'from',
        'join',
        'joinInner',
        'joinLeft',
        'joinRight',
        'joinFull',
        'joinCross',
        'joinNatural',
        'where',
        'orWhere',
        'insert',
        'insertFromSelect',
        'query',
        'columns',
        'limit',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $methodName = $phpcsFile->getTokens()[$stackPtr]['content'];
        static $fileName;
        static $calledMethods;
        if ($fileName != $phpcsFile->getFilename()) {
            $fileName = $phpcsFile->getFilename();
            $calledMethods = array_flip(array_column($this->getCalledMethods($phpcsFile), 'content'));
        }
        if (isset($calledMethods[$methodName]) && in_array($methodName, $this->disallowedMethods)) {
            if (!$this->isInResourceModel($phpcsFile)) {
                $phpcsFile->addWarning(
                    $this->warningMessage,
                    $stackPtr,
                    $this->warningCode,
                    [strtoupper($methodName)]
                );
            }
        }
    }

    /**
     * Needed pointer to search. Can be class for M1 or namespace for M2
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @return mixed
     */
    protected function getNeededPointer(PHP_CodeSniffer_File $phpcsFile)
    {
        $tokens = $phpcsFile->getTokens();
        return array_search($this->token, array_column($tokens, 'code'));
    }

    /**
     * Check if class is Resource Model
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @return bool
     */
    protected function isInResourceModel(PHP_CodeSniffer_File $phpcsFile)
    {
        $neededPointer = $this->getNeededPointer($phpcsFile);
        if ($neededPointer !== false) {
            $classPointer = $phpcsFile->findNext(T_STRING, ($neededPointer + 1));
            if ($classPointer !== false) {
                $className = $phpcsFile->getTokens()[$classPointer]['content'];
                return $this->isInResourceModelFlag($className);
            }
        }
        return false;
    }

    /**
     * Check if string contains substring
     *
     * @param $stringToSearch
     * @return bool
     */
    protected function isInResourceModelFlag($stringToSearch)
    {
        return strpos($stringToSearch, $this->resourceModel) !== false;
    }
}
