<?php namespace FelipeMarques\JooxMakerTool\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class CustomPrice implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (isset($_POST['joox']) && isset($_POST['joox']['creation']) && $_POST['joox']['creation']['totalPrice'] > 0) {
            $item = $observer->getEvent()->getData('quote_item');
            $item = ($item->getParentItem() ? $item->getParentItem() : $item);
            $price = floatval($_POST['joox']['creation']['totalPrice']); //set your price here
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
        }
    }
}