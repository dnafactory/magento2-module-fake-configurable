<?php
namespace DNAFactory\FakeConfigurable\Model\Config\Source;

class ProductAttributes implements \Magento\Framework\Option\ArrayInterface
{
    protected \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory;

    public function __construct (
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function toOptionArray()
    {
        $attributes = $this->attributeCollectionFactory->create();
        $options = [
            [
                'value' => 0,
                'label' => __("none")
            ]
        ];

        /** @var \Magento\Eav\Model\Entity\Attribute $attribute */
        foreach ($attributes as $attribute) {
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getName()
            ];
        }

        return $options;
    }
}
