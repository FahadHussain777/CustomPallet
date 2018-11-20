<?php
/**
 * Copyright Express Commerce
 * User: root
 * Date: 8/27/18
 * Time: 7:52 PM
 */

namespace Ec\CustomPallet\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_pallet_type',
            [
                'group' => 'General',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Custom Pallet Type',
                'input' => 'select',
                'class' => '',
                'source' => 'Ec\CustomPallet\Model\Product\Attribute\Source\Select',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '0',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to'=>'custompallet'
            ]
        );
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $fieldList = [
            'price',
            'special_price',
            'special_from_date',
            'special_to_date',
            'minimal_price',
            'cost',
            'tier_price',
            'tax_class_id',
            'weight',
        ];
        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(\Ec\CustomPallet\Model\Product\Type\CustomPallet::TYPE_ID, $applyTo)) {
                $applyTo[] = \Ec\CustomPallet\Model\Product\Type\CustomPallet::TYPE_ID;
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }

    }
}