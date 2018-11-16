<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class PublicClassMembersSniff
 * Detects use of public methods and properties in Actions and Observers.
 */
class PublicClassMembersSniff implements Sniff
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
    protected $warningMessage = 'The use of public %s in %s is discouraged.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'PublicClassMemberFound';

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
     * @var File
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
    public function process(File $phpcsFile, $stackPtr)
    {
        //do nothing if it's an abstract class
        if ($phpcsFile->findPrevious(T_ABSTRACT, $stackPtr - 1) !== false) {
            return;
        }
        $this->tokens = $phpcsFile->getTokens();
        $this->file = $phpcsFile;
        $class = $this->foundInObserver($stackPtr) ?: $this->foundInAction();
        if ($class !== false) {
            $start = $this->tokens[$stackPtr]['scope_opener'];
            while (($publicPosition = $phpcsFile->findNext(
                    T_PUBLIC,
                    $start + 1,
                    $this->tokens[$stackPtr]['scope_closer'] - 1
                )) !== false) {
                $foundPosition = $phpcsFile->findNext([T_STRING, T_VARIABLE], $publicPosition + 1);
                $found = $this->tokens[$foundPosition]['type'] === 'T_STRING' ? 'method' : 'property';
                $this->processWarning($foundPosition, $class, $found);
                $start = $foundPosition + 1;

            }
        }
    }

    /**
     * Process warning message if method name is not in allowed list.
     *
     * @param int $methodPosition
     * @param string $class
     * @param string $found
     * @return void
     */
    private function processWarning($methodPosition, $class, $found)
    {
        if (!in_array($this->tokens[$methodPosition]['content'], $this->allowedMethods[$class])
            || $found === 'property') {
            $this->file->addWarning(
                $this->warningMessage,
                $methodPosition,
                $this->warningCode,
                [$found, strtoupper($class)],
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
