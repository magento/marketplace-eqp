<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP1\Sniffs\PHP;

use PHP_CodeSniffer as Sniffer;
use PHP_CodeSniffer_File as File;
use Generic_Sniffs_PHP_SyntaxSniff as GenericSyntax;

/**
 * Class SyntaxSniff
 * Ensures PHP believes the syntax is clean..
 */
class SyntaxSniff extends GenericSyntax
{
    /**
     * Violation severity.
     *
     * @var int
     */
    protected $severity = 10;

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $errorMessage = 'PHP syntax error: %s';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $errorCode = 'PHPSyntax';

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpPath = Sniffer::getConfigData('php5.4_path');
        $this->execute($phpcsFile, $phpPath);
    }//end process()

    protected function execute(File $phpcsFile, $phpPath)
    {
        if ($phpPath === null) {
            // PHP_BINARY is available in PHP 5.4+.
            if (defined('PHP_BINARY') === true) {
                $phpPath = PHP_BINARY;
            } else {
                return;
            }
        }
        $fileName = $phpcsFile->getFilename();
        $cmd = "$phpPath -l \"$fileName\" 2>&1";
        $output = shell_exec($cmd);

        $matches = [];
        if (preg_match('/^.*error:(.*) in .* on line ([0-9]+)/', trim($output), $matches) === 1) {
            $error = trim($matches[1]);
            $line = (int)$matches[2];
            $phpcsFile->addErrorOnLine($this->errorMessage, $line, $this->errorCode, [$error], $this->severity);
        }

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);
    }
}
