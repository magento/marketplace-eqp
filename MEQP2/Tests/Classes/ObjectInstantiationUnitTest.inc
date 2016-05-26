<?php
namespace Testsource\Example\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\Framework\Model\Context;

class TestHelper
{
    protected $_objectManager;
    protected $_tester;
    protected $order;

    public function __construct(
        ObjectManagerInterface $objectManager,
        TesterInterface $tester,
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_tester = new TesterHelper($tester, $scopeConfig);
        $this->_objectManager = $objectManager;
        $this->order = new \Magento\Sales\Model\Order($context);
		$this->obj = new \StdObject();
    }
	
	public function test($notification)
    {
        if (!$notification->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Wrong notification ID specified.'));
        }
    }
}
