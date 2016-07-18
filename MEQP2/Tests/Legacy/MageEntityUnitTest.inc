<?php

$model = Mage :: getModel();
$label = someMethod()->getAttributeRawValue($categoryId, 'name', Mage::app()->getStore());

$staticCall = SomeClass::call();
