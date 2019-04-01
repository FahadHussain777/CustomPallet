<?php
/**
 * Copyright Express Commerce
 * User: root
 * Date: 8/27/18
 * Time: 10:07 PM
 */

namespace Ec\CustomPallet\Block\Product\View\Element;

use Magento\Catalog\Model\Product;
use \Magento\Checkout\Model\Cart;

class Template extends \Magento\Framework\View\Element\Template
{
    protected $productModel;

    protected $cart;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Product $productModel,
        Cart $cart,
        array $data = []
)
    {
        $this->productModel = $productModel;
        $this->cart = $cart;
        parent::__construct($context, $data);
    }

    public function getPalletType($id){
        $product = $this->productModel->load($id);
        return $product->getResource()->getAttribute('custom_pallet_type')->getFrontend()->getValue($product);
    }

    public function getAdditionalOptions(){
        $product = $this->cart
            ->getQuote()
            ->getItemById(
                $this->getRequest()->getParam('id')
            );
        $option = $product->getOptionByCode('additional_options');
        if(isset($option)){
            return $option->getValue();
        }else{
            return json_encode(null);
        }
    }

    public function isCartUpdatePage(){
        if($this->getRequest()->getActionName() == 'configure'){
            return true;
        }
        else{
            return false;
        }
    }
}