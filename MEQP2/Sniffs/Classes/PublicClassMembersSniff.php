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
     * List of classes to check.
     *
     * @var array
     */
    protected $map = [
        'action' =>
            [
                'implements' => 'ActionInterface',
                'allowedMethods' =>
                    ['__construct', 'execute', 'dispatch']
            ],
        'observer' =>
            [
                'implements' => 'ObserverInterface',
                'allowedMethods' =>
                    ['__construct', 'execute',]
            ]
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
        // for now do nothing if it's an abstract class
        // ToDo: take into account https://github.com/magento/magento2/issues/9582
        if ($phpcsFile->findPrevious(T_ABSTRACT, $stackPtr - 1) !== false) {
            return;
        }
        $this->tokens = $phpcsFile->getTokens();
        $this->file = $phpcsFile;
        $class = $this->findClass($stackPtr);
        if ($class === false) {
            return;
        }
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
        if (!in_array($this->tokens[$methodPosition]['content'], $this->map[$class]['allowedMethods'])
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
     * Returns true if class implements one of the specified interfaces.
     *
     * @param int $start
     * @param string where
     * @return bool
     */
    private function isInterfaceFound($start, $where)
    {
        $implementsPosition = $this->file->findNext(T_IMPLEMENTS, $start + 1);
        if ($implementsPosition !== false) {
            return $this->file->findNext(
                T_STRING,
                $implementsPosition + 1,
                $this->tokens[$start]['scope_opener'],
                false,
                $where
            ) !== false;
        }
        return false;
    }

    /**
     * Returns specific class or false otherwise.
     *
     * @param $start
     * @return string|bool
     */
    private function findClass($start)
    {
        foreach ($this->map as $key => $item) {
            if ($this->isInterfaceFound($start, $item['implements'])) {
                return $key;
            }
        }
        return false;
    }
}
