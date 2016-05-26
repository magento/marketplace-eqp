<?php
/**
 * Module shema installation
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @version 5.0.0
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order'),
            'my_column',
            'text;'
        );
        $setup->getConnection()->modifyColumn(
            $setup->getTable('sales_order'),
            'column_name',
            'definition'
            );
        $setup->getConnection()->changeColumn(
            $setup->getTable('sales_order'),
            'old_name',
            'new_name',
            'definition'
        );
        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order'),
            'old_name'
        );

        $setup->getConnection()->dropTable($setup->getTable('catalog_product_entity'));

        $setup->getConnection()->truncateTable(
            $setup->getTable('sales_order')
        );
        $setup->getConnection()->renameTable(
            $setup->getTable('sales_order'),
            'new_table_name'
        );
        $setup->getConnection()->addForeignKey(
            'foreignKeyName',
            $setup->getTable('sales_order'),
            'column_name_name',
            'refTableName',
            'refColumnName'
        );
        $setup->getConnection()->addIndex(
            $setup->getTable('sales_order'),
            'index_name',
            'columns'
        );
        $setup->getConnection()->dropForeignKey(
            $setup->getTable('sales_order'),
            'foreignKeyName'
        );
        $setup->getConnection()->dropIndex(
            $setup->getTable('sales_order'),
            'indexName'
        );
        
        $tableName = $setup->getTable('catalog_product_entity');
        $setup->getConnection()->dropTable($tableName);
    }
}