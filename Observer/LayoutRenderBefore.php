<?php namespace FelipeMarques\JooxMakerTool\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class LayoutRenderBefore implements ObserverInterface
{

    protected $_logger;
    protected $_layout;

    /**
     * @param LoggerInterface $logger
     * @param \Magento\Framework\View\Layout\Builder $layout
     */
    public function __construct(
        LoggerInterface $logger,
        \Magento\Framework\View\Layout\Builder $layout){

        $this->_logger = $logger;
        $this->_layout = $layout;

    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventName = $observer->getEvent()->getName();
        $this->_logger->info(__METHOD__ . '[OBSERVER EVENT CATCHED]: ' . $eventName );

        //echo '<script>alert("LayoutRenderBefore -> ObserverInterface")</script>';
    }
}