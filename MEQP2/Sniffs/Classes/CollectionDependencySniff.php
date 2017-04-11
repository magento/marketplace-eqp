<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MEQP2\Sniffs\Classes;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;
use \Utils\Helper;

/**
 * Class CollectionDependencySniff
 * Detects possible misusage of Magento repositories.
 */
class CollectionDependencySniff implements PHP_CodeSniffer_Sniff
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
     * 'as' keyword in usages.
     *
     * @var string
     */
    const AS_KEYWORD = ' as ';

    /**
     * __construct method().
     *
     * @var string
     */
    const CONSTRUCT_METHOD = '__construct';

    /**
     * Grid class identifier.
     *
     * @var string
     */
    const GRID_METHOD = 'setCollection';

    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $warningMessage = '%s should be used instead of %s.';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $warningCode = 'CollectionDependency';

    /**
     * Data, which will be shared upon class methods.
     *
     * @var array
     */
    protected $sharedData = [];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_USE, T_FUNCTION];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $sourceFile, $index)
    {
        if (\PHP_CodeSniffer::getConfigData('m2-path')) {
            $this->initPreferences();
            $this->sharedData['file'] = $sourceFile;
            $this->sharedData['index'] = $index;
            $this->sharedData['tokens'] = $sourceFile->getTokens();
            if (!$this->sharedData['preferences'] || $this->isGridHandler() || $this->isRepository()) {
                return;
            }
            $classPointer = array_search(T_CLASS, array_column($this->sharedData['tokens'], 'code'));
            if ($index < $classPointer && $this->sharedData['tokens'][$index]['code'] === T_USE) {
                $this->processUse();
            }
            if ($this->sharedData['tokens'][$index]['code'] === T_FUNCTION
                && $sourceFile->getDeclarationName($index) === self::CONSTRUCT_METHOD
            ) {
                $listOfDependencies = $this->getAvailableTypeHints($sourceFile->getMethodParameters($index));
                foreach ($listOfDependencies as $dependency) {
                    $this->processWarning($dependency);
                }
            }
        }
    }

    /**
     * Initialize Magento preferences.
     *
     * @return void
     */
    private function initPreferences()
    {
        if (!array_key_exists('preferences', $this->sharedData)) {
            $objectManager = $this->getObjectManager();
            $configLoader = $objectManager->create(
                \Magento\Framework\App\ObjectManager\ConfigLoader::class,
                [
                    'readerFactory' => $objectManager->create(
                        \Magento\Framework\ObjectManager\Config\Reader\DomFactory::class
                    ),
                ]
            );
            // @codingStandardsIgnoreLine
            $this->sharedData['preferences'] = $configLoader->load(\Magento\Framework\App\Area::AREA_GLOBAL)['preferences'];
        }
    }

    /**
     * Process class use.
     *
     * @return void
     */
    private function processUse()
    {
        $source = '';
        $nextUse = $this->sharedData['file']->findNext(T_SEMICOLON, $this->sharedData['index']);
        for ($i = $this->sharedData['file']->findNext(T_STRING, $this->sharedData['index']); $i <= $nextUse - 1; $i++) {
            $source .= $this->sharedData['tokens'][$i]['content'];
        }
        $usages = explode(',', $source);
        foreach ($usages as $usage) {
            if (strpos($usage, self::AS_KEYWORD) !== false) {
                $usage = substr($usage, 0, strpos($usage, self::AS_KEYWORD));
            }
            $this->processWarning($usage);
        }
    }

    /**
     * Add warning if specified source has repository.
     *
     * @param PHP_CodeSniffer_File $source
     * @return void
     */
    private function processWarning($source)
    {
        if ($this->isCollection($source)) {
            $repository = $this->getRepositoryPath($source);
            if (in_array($repository, $this->sharedData['preferences'])) {
                $replacement = [array_search($repository, $this->sharedData['preferences']), $source];
                $this->sharedData['file']->addWarning(
                    $this->warningMessage,
                    $this->sharedData['index'],
                    $this->warningCode,
                    $replacement,
                    $this->severity
                );
            }
        }
    }

    /**
     * Get list of type hints present in __construct() method.
     *
     * @param array $arguments
     * @return array
     */
    private function getAvailableTypeHints(array $arguments)
    {
        $data = [];
        foreach ($arguments as $argument) {
            if ($argument['type_hint'] !== null) {
                $data[$argument['name']] = $argument['type_hint'];
            }
        }
        return $data;
    }

    /**
     * Check if specified path is collection class.
     *
     * @param string $path
     * @return bool
     */
    private function isCollection($path)
    {
        return strpos($path, 'Collection') !== false;
    }

    /**
     * Check if Grid handler class.
     *
     * @return bool
     */
    private function isGridHandler()
    {
        static $fileName;
        static $methods;
        if ($fileName != $this->sharedData['file']->getFilename()) {
            $fileName = $this->sharedData['file']->getFilename();
            $methods = array_flip(array_column($this->getCalledMethods($this->sharedData['file']), 'content'));
        }
        return isset($methods[self::GRID_METHOD]);
    }

    /**
     * Check if processed file is repository.
     *
     * @return bool
     */
    private function isRepository()
    {
        return strpos($this->sharedData['file']->getFileName(), 'Repository') !== false;
    }

    /**
     * Convert collection path to possible repository path.
     *
     * @param string $path
     * @return string
     */
    private function getRepositoryPath($path)
    {
        $path = trim($path, '\\');
        $needle = ['Factory', '\ResourceModel', '\Collection'];
        $replacement = ['', '', 'Repository'];
        return str_replace($needle, $replacement, $path);
    }
}
