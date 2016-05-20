<?php namespace FelipeMarques\JooxMakerTool\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class CheckoutCartUpdateItemsBefore implements ObserverInterface
{

    protected $_logger;

    public function __construct(LoggerInterface $logger){
        $this->_logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventName = $observer->getEvent()->getName();
        $this->_logger->info(__METHOD__ . '[OBSERVER EVENT CATCHED]: ' . $eventName );
    }
}