<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\SQL;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Tokenizers\PHP as Tokenizer;
use MEQP\Utils\Helper;

/**
 * Class CoreTablesModificationSniff
 * Detects possible core table modifications.
 */
class CoreTablesModificationSniff implements Sniff
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
     * Name of file with database core tables.
     *
     * @var string
     */
    const CORE_TABLES = 'core_tables.json';

    /**
     * @var string
     */
    const DS = DIRECTORY_SEPARATOR;
    /**
     * String representation of error.
     *
     * @var string
     */
    protected $warningMessage = 'Modification of magento database core table %s';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'CoreTablesModification';

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
     * Array of deprecatedMethods which calls to modify core tables.
     * Key table name and value parameter number in method call which needs to check.
     *
     * @var array
     */
    protected $deprecatedMethods = [
        'addColumn' => 1,
        'modifyColumn' => 1,
        'changeColumn' => 1,
        'dropColumn' => 1,
        'dropTable' => 1,
        'truncateTable' => 1,
        'renameTable' => 1,
        'addForeignKey' => 2,
        'addIndex' => 1,
        'dropForeignKey' => 1,
        'dropIndex' => 1,
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
    public function process(File $sourceFile, $index)
    {
        if (!empty(Config::getConfigData('m2-path')
            && in_array($sourceFile->getDeclarationName($index), $this->schemaClasses))
        ) {
            $methods = $this->getCalledMethods($sourceFile);
            $deprecatedMethods = array_filter($methods, function ($element) {
                return isset($this->deprecatedMethods[$element['content']]);
            });
            $tokens = $sourceFile->getTokens();
            $coreTables = $this->getCoreTables();
            foreach ($deprecatedMethods as $stack => $val) {
                $position = $sourceFile->findNext(T_CONSTANT_ENCAPSED_STRING, $stack + 1);
                $tableName = isset($tokens[$position]['content']) ? $tokens[$position]['content'] : false;
                if ($tableName && isset($coreTables[$tableName])) {
                    $sourceFile->addWarning(
                        $this->warningMessage,
                        $position,
                        $this->warningCode,
                        [$tableName],
                        $this->severity
                    );
                }
            }
        }
    }

    /**
     * Gets array of database core tables.
     *
     * @return array
     */
    private function getCoreTables()
    {
        return empty(Config::getConfigData('core_tables'))
            ? $this->getCachedTables()
            : Config::getConfigData('core_tables');
    }

    /**
     * Gets array of database cached core tables.
     *
     * @return mixed
     */
    private function getCachedTables()
    {
        $cachedFileName = $this->getBasePath() . self::DS . 'cache' . self::DS . self::CORE_TABLES;
        if (!file_exists($cachedFileName)) {
            return $this->setCachedTables($cachedFileName);
        }
        $coreTables = json_decode(file_get_contents($cachedFileName), true);
        Config::setConfigData(
            'core_tables',
            $coreTables,
            true
        );
        return $coreTables;
    }

    /**
     * Setting database core table into cache file, PHP_CodeSniffer configData
     * and returns it.
     *
     * @param string $cachedFileName
     * @returns array
     */
    private function setCachedTables($cachedFileName)
    {
        $m2path = Config::getConfigData('m2-path');
        try {
            require $m2path . self::DS . 'app' . self::DS . 'bootstrap.php';
        } catch (\Exception $e) {
            die($e->getMessage() . PHP_EOL);
        }
        $componentRegister = new \Magento\Framework\Component\ComponentRegistrar;
        $moduleList = $componentRegister->getPaths($componentRegister::MODULE);
        $magentoModules = array_filter($moduleList, function ($module) {
            return (strpos($module, 'Magento_') === 0) ? 1 : 0;
        }, ARRAY_FILTER_USE_KEY);

        $coreTables = [];
        foreach ($magentoModules as $moduleName => $path) {
            $installSchema = $path . DIRECTORY_SEPARATOR . 'Setup' . DIRECTORY_SEPARATOR . 'InstallSchema.php';
            if (file_exists($installSchema)) {
                $content = file_get_contents($installSchema);
                $eolChar = Common::detectLineEndings($content);
                $tokenizer = new Tokenizer($content, null, $eolChar);

                //$tabWidth, encoding
                $tokens = $tokenizer->getTokens();

                $newTables = array_filter($tokens, function ($val) {
                    return ($val['code'] == T_STRING && $val['content'] == 'newTable') ? 1 : 0;
                });
                foreach ($newTables as $index => $newTable) {
                    while ($tokens[$index]['code'] != T_CONSTANT_ENCAPSED_STRING) {
                        $index++;
                    }
                    $coreTables[$tokens[$index]['content']] = $moduleName;
                }
            }
        }
        $dirName = $this->getBasePath() . self::DS . 'cache';
        if (!is_dir($dirName)) {
            mkdir($dirName);
        }
        file_put_contents($cachedFileName, json_encode($coreTables));
        Config::setConfigData(
            'core_tables',
            $coreTables,
            true
        );
        return $coreTables;
    }
}
