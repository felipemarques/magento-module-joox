<?php namespace FelipeMarques\Hello\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class CustomPrice implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);
        $price = 100; //set your price here
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->getProduct()->setIsSuperMode(true);
    }
}