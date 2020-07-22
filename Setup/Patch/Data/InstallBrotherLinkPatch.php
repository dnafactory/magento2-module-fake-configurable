<?php

namespace DNAFactory\FakeConfigurable\Setup\Patch\Data;

use DNAFactory\FakeConfigurable\Model\Product\Brother\Link;
use DNAFactory\FakeConfigurable\Ui\DataProvider\Product\Form\Modifier\Brother;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallBrotherLinkPatch implements DataPatchInterface
{
    protected $moduleDataSetup;
    /**
     * @var SchemaSetupInterface
     */
    protected $setup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        SchemaSetupInterface $setup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->setup = $setup;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->setup->startSetup();

        $catalogProductLinkTypeData = [
            'link_type_id' => Link::LINK_TYPE_BROTHER,
            'code' => Brother::DATA_SCOPE_BROTHER
        ];

        $this->setup->getConnection()->insertOnDuplicate(
            $this->setup->getTable('catalog_product_link_type'),
            $catalogProductLinkTypeData
        );

        $catalogProductLinkAttributeData = [
            'link_type_id' => Link::LINK_TYPE_BROTHER,
            'product_link_attribute_code' => 'position',
            'data_type' => 'int',
        ];

        $this->setup->getConnection()->insert(
            $this->setup->getTable('catalog_product_link_attribute'),
            $catalogProductLinkAttributeData
        );

        $this->setup->endSetup();
    }
}
