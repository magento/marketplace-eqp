<?php

class TestCollectionCount
{
    public function testFunction()
    {
        $productCollection = Mage::getModel('catalog/product')->getCollection();
        $count = $productCollection->count();
        $count2 = Mage::getModel('catalog/product')->getCollection()->count();
    }
}
