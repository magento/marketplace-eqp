<?php

class BindCustomerLoginObserver implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        Item $item
    )
    {
        $this->item = $item;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->item->bindCustomerLogin();
        return $this;
    }

    public function notAllowed()
    {
        //
    }
}
