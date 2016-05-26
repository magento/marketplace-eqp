<?php

class Vendor_Module_Block_Adminhtnl_View extends Mage_Core_Block_Template
{
    protected function getLayer()
    {
        $this->getLayout()->createBlock('catalog/breadcrumbs');
        $category =  Mage::getResourceModel('catalog/category');
        $adapterSelect = $category->getReadConnection()
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


        $adapter = $category->getReadConnection()
            ->insert('data')
            ->insertFromSelect($adapterSelect);

        $adapter = $category->getReadConnection()
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
