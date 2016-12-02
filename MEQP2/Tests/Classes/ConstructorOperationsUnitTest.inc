<?php

class Correct
{
    public function __construct($fileReader, $context, $resource, $indexerConfig, $indexHandlerFactory)
    {
        $this->_code = static::METHOD_CODE;
        $this->connection = $resource->getConnection();
        $this->test = ((($resource)));
        $this->eventManager = $context->getEventManager();
        $this->fileReader = $fileReader;
        parent::__construct($context);
    }
}

class NotAllowedFunctionCall
{
    public function __construct($indexHandlerFactory, $configData, $fileReader, $eventManager)
    {
        $this->indexHandler = $indexHandlerFactory->create(['data' => $configData]);
        $this->data = $fileReader->read('cache.xml');
        $eventManager->dispatch('config_read_after');
    }
}

class ArithmeticOperations
{
    public function __construct($context)
    {
        $this->code = $context->getIndexCode() + 300;
    }
}

class AssignmentNotToClassMember
{
    public function __construct($indexerConfig)
    {
        $configData = $indexerConfig->getIndexer(Fulltext::INDEXER_ID);
    }
}

class ParentFunctionCall
{
    public function __construct($eventManager, $context)
    {
        $this->eventManager = $eventManager;
        parent::_construct($context);
    }
}

class IfCondition
{
    public function __construct($fileReader, $eventManager)
    {
        $this->eventManager = $eventManager;
        if ($fileReader) {
            $this->fileReader = $fileReader;
        }
    }
}

class WhileCycle
{
    public function __construct($eventManager, $files)
    {
        $this->eventManager = $eventManager;
        $this->files = [];
        $i = 10;
        while($i > 1) {
            $this->files[] = $files[$i];
            $i--;
        }
    }
}

class DoWhileCycle
{
    public function __construct($eventManager, $files)
    {
        $this->files = [];
        $i = 10;
        do {
            $this->files[] = $files[$i];
            $i--;
        } while($i > 1);
        $this->eventManager = $eventManager;
    }
}

class ForCycle
{
    public function __construct($eventManager, $files)
    {
        $this->files = [];
        $filesCount = count($files);
        for ($i = 0; $i < $filesCount; $i++) {
            $this->files[] = $files[$i];
        }
        $this->eventManager = $eventManager;
    }
}

class ForeachCycle
{
    public function __construct($eventManager, $files)
    {
        $this->eventManager = $eventManager;
        $this->files = [];
        foreach ($files as $path => $data) {
            $this->files[realpath($path)] = $data;
        }
    }
}

class SwitchStatement
{
    public function __construct($fileReader, $eventManager)
    {
        $this->eventManager = $eventManager;
        switch (gettype($fileReader)) {
            case 'A':
                $this->fileReader = $fileReader;
                break;

            case 'B':
                $this->fileReader = $fileReader->getParent();
                break;
        }
    }
}
