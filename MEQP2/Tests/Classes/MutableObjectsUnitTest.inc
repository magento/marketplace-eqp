<?php
use \Magento\Framework\Search\Request\Config as SearchRequestConfig;
use Magento\Customer\Model\Session as Customer;
use Magento\Checkout\Model\SessionManager as Manager;
use Magento\Checkout\Model\Session\Factory as Factory2;
use Magento\Checkout\Model\Session as Factory;
use Magento\Session\Model\Backend as Backend;
use \Magento\Framework\App\Request\Http as TT, \Magento\Framework\App\Request\Http;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Search\Request\Dimension;

class SomeClass
{
    protected $factory;
    protected $session;
    protected $session2;
    protected $sessionManager2;
    protected $backend;
    protected $tt;
    protected $cookie;
    protected $cookie2;
    protected $exam;
    protected $eater;

    public function __construct(
        Factory $factory,
        Magento\Session\Model\Session $session,
        Magento\Checkout\Model\SessionManager $session2,
        \Framework\Session\SessionManagerInterface $sessionManager2,
        Backend $backend,
        \Magento\Framework\App\Request\Http $request2,
        Http $http,
        TT $tt,
        \Magento\Checkout\Model\Cookie $cookie,
        Magento\Cookie\Model\Config\Backend $cookie2,
        Vendor\Extension\Model\Session\Exam $exam,
        Vendor\Extension\Model\Cookie\Eater $eater,
        PublicCookieMetadata $metaData,
        \Magento\Framework\Stdlib\Cookie\CookieReaderInterface $cookieReader,
        \Magento\Persistent\Model\SessionFactory $sessionFactory,
        Magento\Framework\App\RequestInterface $requestInterface,
        Dimension $dimension
    ) {
        $this->factory = $factory;
        $this->session = $session;
        $this->session2 = $session2;
        $this->sessionManager2 = $sessionManager2;
        $this->backend = $backend;
        $this->tt = $tt;
        $this->request2 = $request2;
        $this->http = $http;
        $this->cookie = $cookie;
        $this->cookie2 = $cookie2;
        $this->exam = $exam;
        $this->eater = $eater;
        $this->eater = $eater;
        $this->metaData = $metaData;
        $this->cookieReader = $cookieReader;
        $this->ssessionFactory = $sessionFactory;
        $this->requestInterface = $requestInterface;
        $this->dimension = $dimension;
    }
}

class SomeClass3 implements \Magento\Framework\Authorization\RoleLocatorInterface
{
    protected $checkoutSession;

    public function __conStrUct(
        \Magento\Checkout\Model\Session $checkoutSession//+
    ) {
        $this->checkoutSession = $checkoutSession;
    }
}

class SomeClass2 implements \Magento\Framework\Authorization\RoleLocatorInterface
{
    protected function joinCall()
    {
        return true;
    }

    protected function testMethod()
    {
        $this->joinCall();
    }
}
