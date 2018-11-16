<?php

class Save implements \Magento\Framework\App\ActionInterface
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->logger = $logger;
    }

    public function dispatch()
    {
        //
    }

    public function notAllowed()
    {
        //
    }

    private function allowed()
    {
        //
    }

    public $notAllowedProperty;
}
