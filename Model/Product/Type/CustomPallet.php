<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/10/18
 * Time: 3:49 PM
 */

namespace Ec\CustomPallet\Model\Product\Type;


class CustomPallet extends \Magento\Catalog\Model\Product\Type\Simple
{
    const TYPE_ID = 'custompallet';

    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intensionally empty
    }

}