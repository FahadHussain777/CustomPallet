<?php
/**
 * Copyright Express Commerce
 */

namespace Ec\CustomPallet\Observer;

use Magento\Framework\Event\Observer;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Ec\CustomPallet\Model\Inventory\QuoteManagement;

class SalesModelServiceQuoteSubmitFailure implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var $quoteItems
     */
    private $quoteItems ;
    /**
     * @var $quote
     */
    private $quote;
    /**
     * @var $order
     */
    private $order;

    private $quoteManagement;

    private $stockManagement;

    public function __construct(
        QuoteManagement $quoteManagement,
        StockConfigurationInterface $stockConfiguration
    ){
        $this->quoteManagement = $quoteManagement;
        $this->stockManagement = $stockConfiguration;
    }

    public function execute(Observer $observer)
    {
        $this->quote = $observer->getQuote();
        $this->order = $observer->getOrder();

        foreach($this->order->getItems() as $orderItem)
        {
            if(!$orderItem->getParentItemId() && $orderItem->getProductType() == 'custompallet')
            {
                $quoteItem = $this->getQuoteItemById($orderItem->getQuoteItemId());
                $additionalOptionsQuote = $quoteItem->getOptionByCode('additional_options');
                if($this->stockManagement->canSubtractQty())
                    $this->quoteManagement->revertProductSale(json_decode($additionalOptionsQuote->getValue(),true),$orderItem->getProductId());
            }
        }
    }

    /**
     * @param $id
     * @return null
     */
    private function getQuoteItemById($id)
    {
        if(empty($this->quoteItems))
        {
            /* @var  \Magento\Quote\Model\Quote\Item $item */
            foreach($this->quote->getItems() as $item)
            {
                //filter out config/bundle etc product
                if(!$item->getParentItemId() && $item->getProductType() == 'custompallet')
                {
                    $this->quoteItems[$item->getId()] = $item;
                }
            }
        }
        if(array_key_exists($id, $this->quoteItems))
        {
            return $this->quoteItems[$id];
        }
        return null;
    }

}