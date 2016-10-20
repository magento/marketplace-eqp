<?php

$this->objectManager->get('Magento\Catalog\Model\Product');

$this->objectManager->get('Magento\Catalog\Model\NotExistingClass');

$this->objectManager->get(\Magento\Catalog\Model\Product::class);

$this->objectManager->get('Magento\Catalog\Model' . $class);
