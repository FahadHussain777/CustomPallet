<?php

namespace Ec\CustomPallet\Model\Inventory;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\CatalogInventory\Model\StockManagement;

/**
 * Class QuoteManagement
 * @package Ec\CustomPallet\Model\Inventory
 */
class QuoteManagement
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var StockManagement
     */
    protected $stockManagement;


    /**
     * QuoteManagement constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param StockManagement $stockManagement
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        StockManagement $stockManagement
    ){
        $this->productRepository = $productRepository;
        $this->stockManagement = $stockManagement;
    }


    public function revertProductSale($selectedOptions, $productId){

    }

    /**
     * @param $selectedOptions
     * @param $productId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function registerProductsSale($selectedOptions, $productId){
        $product = $this->productRepository->getById($productId);
        $customOptions = $product->getOptions();
        $optionsSku = [];
        $optionsOrdered = [];
        $optionsQty = [];
        /*
         * $optionsOrdered: array initialized for counting ordered options, later
         * $optionsSku: array of option title with respective sku, null if it does'nt exist
         * $optionsQty: array with options title against their quantity
         */
        foreach ($customOptions as $option){
            $title = $option->getTitle();
            $title = strtolower($title);
            if(strpos($title,'face') !== false){
                $index = 'face';
                $optionsSku[$index] = [];
                $optionsOrdered[$index] = [];
                $optionsQty[$index] = [];
            }
            if (strpos($title,'eye') !== false){
                $index = 'eye';
                $optionsSku[$index] = [];
                $optionsOrdered[$index] = [];
                $optionsQty[$index] = [];
            }
            foreach ($option->getValues() as $value){
                $optionsSku[$index][$value->getTitle()] = $value->getSku();
                $optionsQty[$index][$value->getTitle()] = (int) $value->getQty();
                $optionsOrdered[$index][$value->getTitle()] = 0;
            }
        }
        /*
         * Gathering arrays of selected items
         */
        foreach ($selectedOptions as $selectedOption){
            $label = $selectedOption['label'];
            $label = strtolower($label);
            if(strpos($label,'eye') !== false){
                $option_eye =array_map('trim', explode(',', $selectedOption['value']));
            }
            if (strpos($label,'face') !== false){
                $option_face =array_map('trim', explode(',', $selectedOption['value']));
            }
        }

        /*
         * Counting every item an merging with $optionsOrdered
         */
        if(isset($option_eye)){
            $eye_counts = array_count_values($option_eye);
            $optionsOrdered['eye'] = array_replace_recursive($optionsOrdered['eye'],$eye_counts);
        }
        if(isset($option_face)){
            $face_counts = array_count_values($option_face);
            $optionsOrdered['face'] = array_replace_recursive($optionsOrdered['face'],$face_counts);
        }

        /*
         * Registering product sale for options with sku
         */
        if(isset($optionsSku['eye'])){
            foreach ($optionsSku['eye'] as $title => $sku){
                if($sku !== null){
                    $this->decreaseProductQuantity($sku,$optionsOrdered['eye'][$title]);
                }

            }
        }
        if(isset($optionsSku['face'])){
            foreach ($optionsSku['face'] as $title => $sku){
                if($sku !== null){
                    $this->decreaseProductQuantity($sku,$optionsOrdered['face'][$title]);
                }
            }
        }

        /*
         * Registering product sale for options without sku
         */
        foreach ($customOptions as $option){
            $title = $option->getTitle();
            $title = strtolower($title);
            if(strpos($title,'face') !== false){
                foreach ($option->getValues() as $value){
                    if($optionsSku['face'][$value->getTitle()] == null){
                        $updatedQty = $value->getQty()-$optionsOrdered['face'][$value->getTitle()];
                        $value->setQty($updatedQty);
                        $value->save();
                    }
                }
            }
            if (strpos($title,'eye') !== false){
                foreach ($option->getValues() as $value){
                    if($optionsSku['eye'][$value->getTitle()] == null){
                        $updatedQty = $value->getQty()-$optionsOrdered['eye'][$value->getTitle()];
                        $value->setQty($updatedQty);
                        $value->save();
                    }
                }
            }
        }
    }


    /**
     * @param $productSku
     * @param $orderedQty
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function decreaseProductQuantity($productSku, $orderedQty){
        $optionProduct = $this->productRepository->get($productSku);
        $this->stockManagement->registerProductsSale(
            [$optionProduct->getId() => $orderedQty],
            $optionProduct->getStore()->getWebsiteId()
        );
    }


}