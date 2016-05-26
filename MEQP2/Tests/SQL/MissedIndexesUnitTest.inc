<?php
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->getConnection()->isTableExists($installer->getTable('some_table'))) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('some_table')
            )->addColumn(
                'some_string',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => true],
                'some string'
            );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
