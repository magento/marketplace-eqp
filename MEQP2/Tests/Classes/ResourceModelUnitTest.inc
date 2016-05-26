<?php
namespace MEQP1\Tests\Classes\Block\UnitTest;

class MyBlock extends \Magento\Framework\View\Element\Template
{
    protected function getLayer()
    {
        $category =  new Magento\Catalog\Model\ResourceModel\Category;
        $adapterSelect = $category->getConnection()
            ->select()
            ->reset()
            ->joinInner('table_name')
            ->joinRight('table_name')
            ->joinFull('table_name')
            ->joinCross('table_name')
            ->joinNatural('table_name')
            ->joinLeft('table_name')
            ->where('entity_id != 0')
            ->orWhere('entity_id > 0')
            ->limit(1);
        $lookup = $adapterSelect->fetchRow($category);


        $adapter = $category->getConnection()
            ->insert('data')
            ->insertFromSelect($adapterSelect);
        
        $adapter = $category->getConnection()
            ->query()
            ->columns();
    }

    protected function join()
    {
        return true;
    }

    protected function testMethod()
    {
        $this->join();
    }
}
