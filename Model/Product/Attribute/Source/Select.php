<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/3/18
 * Time: 4:34 PM
 */

namespace Ec\CustomPallet\Model\Product\Attribute\Source;

use Magento\Framework\DB\Ddl\Table;

class Select extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {

        $this->_options=[
            ['label'=>'4 well Palette', 'value'=>'1'],
            ['label'=>'6 well Palette', 'value'=>'2'],
            ['label'=>'lg Palette', 'value'=>'3'],
            ['label'=>'MK07-1 Nudes', 'value'=>'4']
        ];
        return $this->_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}