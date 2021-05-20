<?php
namespace DNAFactory\FakeConfigurable\Model\Config\Source;

class ProductAttributes implements \Magento\Framework\Option\ArrayInterface
{
    protected $attributeFactory;

    public function __construct (
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
    }

    public function toOptionArray()
    {
        $attributes = $this->attributeFactory->getCollection();
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
