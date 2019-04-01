<?php
/**
 * Copyright Express Commerce
 * User: root
 * Date: 8/27/18
 * Time: 10:07 PM
 */

namespace Ec\CustomPallet\Observer;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Ec\CustomPallet\Model\Inventory\QuoteManagement;

/**
 * Class SalesModelServiceQuoteSubmitBefore
 * @package Ec\CustomPallet\Observer
 */
class SalesModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
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

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer){
        $this->quote = $observer->getQuote();
        $this->order = $observer->getOrder();

        foreach($this->order->getItems() as $orderItem)
        {

            if(!$orderItem->getParentItemId() && $orderItem->getProductType() == 'custompallet')
            {


                if($quoteItem = $this->getQuoteItemById($orderItem->getQuoteItemId())){
                    if ($additionalOptionsQuote = $quoteItem->getOptionByCode('additional_options'))
                    {
                        if($additionalOptionsOrder = $orderItem->getProductOptionByCode('additional_options'))
                        {
                            $additionalOptions = array_merge($additionalOptionsQuote, $additionalOptionsOrder);
                        }
                        else
                        {
                            $additionalOptions = $additionalOptionsQuote;
                        }
                        if(count($additionalOptions) > 0)
                        {
                            if($this->stockManagement->canSubtractQty())
                                $this->quoteManagement->registerProductsSale(json_decode($additionalOptions->getValue(),true),$orderItem->getProductId());
                            $options = $orderItem->getProductOptions();
                            $options['additional_options'] = json_decode($additionalOptions->getValue());
                            $orderItem->setProductOptions($options);
                        }

                    }
                }
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
