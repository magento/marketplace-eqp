<?php

$roles = $this->_getReadAdapter()->fetchAll($select);

function fetchAll()
{
    echo 1;
}

fetchAll();

$collection = Mage::getModel('catalog/product')->getCollection();
$item = $collection->getFirstItem();
$item2 = Mage::getModel('catalog/product')->getCollection()->getFirstItem();

