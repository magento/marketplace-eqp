<?php
use Magento\Catalog\Model\ResourceModel\Product\Collection;
class SomeClass
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $factory,
        $data = []
    ) {
        //
    }
}
