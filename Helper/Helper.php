<?php
/**
 * Copyright ExpressCommerce
 * Date: 8/20/18
 * Time: 11:28 PM
 */
namespace Ec\CustomPallet\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;


/**
 * Class Helper
 * @package Ec\CustomPallet\Helper
 */
class Helper
{
    /**
     *  config path for custom product id
     */
    const CUSTOM_PALLET_GENERAL_PRODUCT_IDS = 'custom_pallet/general/product_ids';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;


    /**
     * Helper constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UrlInterface $urlBuilder
    ){
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string (not used)
     */
    public function getCustomProductSku(){
        return $this->scopeConfig->getValue(self::CUSTOM_PALLET_GENERAL_PRODUCT_IDS,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $path
     * @return string
     */
    public function getUrl($path,$param){        
        return $this->urlBuilder->getUrl($path,$param);
    }
}