<?php
/**
 * Copyright Express Commerce
 * User: root
 * Date: 8/27/18
 * Time: 10:07 PM
 */

namespace Ec\CustomPallet\Observer;

class CheckoutCartProductUpdateAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;


    /**
     * CheckoutCartProductAddAfter constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Serialize\Serializer\Json $json
    )
    {
        $this->layout = $layout;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->json = $json;
    }

    public function execute(\Magento\Framework\Event\Observer $observer){
        $params=$this->request->getParams();
        if(isset($params['sheer_face_1']) || isset($params['eye_color_1'])){
            $item = $observer->getQuoteItem();
            $product = $observer->getProduct();
            $palletType = $product->getResource()->getAttribute('custom_pallet_type')->getFrontend()->getValue($product);
            $palletType = str_replace(' ','',$palletType);
            $eyeColorShade = 0;
            $sheerFaceColorShade= 0;
            switch ($palletType) {
                case '4wellPalette':
                    $sheerFaceColorShade = 4;
                    break;
                case '6wellPalette':
                    $eyeColorShade = 6;
                    break;
                case 'lgPalette':
                    $eyeColorShade = 12;
                    $sheerFaceColorShade = 6;
                    break;
                case 'MK07-1Nudes':
                    $eyeColorShade = 4;
                    $sheerFaceColorShade = 3;
                    break;
            }
            $option = [];
            if($eyeColorShade !=0){
                $eyeselected = '';
                for($i=1;$i<=$eyeColorShade;$i++){
                    if($i!==$eyeColorShade)
                        $eyeselected .= $params['eye_color_'.$i].', ';
                    else
                        $eyeselected .= $params['eye_color_'.$i];
                }
                $option[1]= [
                    'label'=>'Eye color shades',
                    'value'=>$eyeselected,
                    'print_value'=>'eye',
                    'option_id'=>'1',
                    'option_type'=>'drop_down',
                    'custom_view'=>false
                ];
            }
            if($sheerFaceColorShade !=0){
                $faceselected = '';
                for($i=1;$i<=$sheerFaceColorShade;$i++){
                    if($i!==$sheerFaceColorShade)
                        $faceselected .= $params['sheer_face_'.$i].', ';
                    else
                        $faceselected .= $params['sheer_face_'.$i];
                }
                $option[2]= [
                    'label'=>'Sheer face color shades',
                    'value'=>$faceselected,
                    'print_value'=>'sheer',
                    'option_id'=>'2',
                    'option_type'=>'drop_down',
                    'custom_view'=>false
                ];
            }
            $option_serialize = $this->json->serialize($option);
            $item->addOption(array(
                'code' => 'additional_options',
                'value' => $option_serialize
            ));
        }
    }
}
