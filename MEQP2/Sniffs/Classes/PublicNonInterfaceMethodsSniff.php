<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Class PublicNonInterfaceMethodsSniff
 * Detects use of public non-interface methods in Actions and Observers.
 */
class PublicNonInterfaceMethodsSniff implements PHP_CodeSniffer_Sniff
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
    protected $warningMessage = 'The use of public non-interface method in %s is discouraged.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'PublicMethodFound';

    /**
     * List of allowed methods.
     *
     * @var array
     */
    protected $allowedMethods = [
        'action' => ['__construct', 'execute', 'dispatch'],
        'observer' => ['__construct', 'execute',],
    ];

    /**
     * All tokens from current file.
     *
     * @var array
     */
    private $tokens;

    /**
     * Current file.
     *
     * @var PHP_CodeSniffer_File
     */
    private $file;

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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        //do nothing if it's an abstract class
        if ($phpcsFile->findPrevious(T_ABSTRACT, $stackPtr - 1) !== false) {
            return;
        }
        $this->tokens = $phpcsFile->getTokens();
        $this->file = $phpcsFile;
        $found = $this->foundInObserver($stackPtr) ?: $this->foundInAction();
        if ($found !== false) {
            $start = $this->tokens[$stackPtr]['scope_opener'];
            while (($publicPosition = $phpcsFile->findNext(
                T_PUBLIC,
                $start + 1,
                $this->tokens[$stackPtr]['scope_closer'] - 1
            )) !== false) {
                $methodPosition = $phpcsFile->findNext(T_STRING, $publicPosition + 1);
                $this->processWarning($methodPosition, $found);
                $start = $methodPosition + 1;
            }
        }
    }

    /**
     * Process warning message if method name is not in allowed list.
     *
     * @param int $methodPosition
     * @param mixed $where
     * @return void
     */
    private function processWarning($methodPosition, $where)
    {
        if (!in_array($this->tokens[$methodPosition]['content'], $this->allowedMethods[$where])) {
            $this->file->addWarning(
                $this->warningMessage,
                $methodPosition,
                $this->warningCode,
                [strtoupper($where)],
                $this->severity
            );
        }
    }

    /**
     * Returns 'observer' if we are in Observer class of 'false' otherwise.
     *
     * @param int $start
     * @return mixed
     */
    private function foundInObserver($start)
    {
        $observerInterfacePosition = false;
        $implementsPosition = $this->file->findNext(T_IMPLEMENTS, $start + 1);
        if ($implementsPosition !== false) {
            $observerInterfacePosition = $this->file->findNext(
                T_STRING,
                $implementsPosition + 1,
                $this->tokens[$start]['scope_opener'],
                false,
                'ObserverInterface'
            );
        }
        return $observerInterfacePosition !== false ? 'observer' : false;
    }

    /**
     * Returns 'action' if we are in Action class (by Controller substring in the file path) of 'false' otherwise.
     *
     * @return mixed
     */
    private function foundInAction()
    {
        return strpos($this->file->getFilename(), 'Controller') !== false ? 'action' : false;
    }
}
