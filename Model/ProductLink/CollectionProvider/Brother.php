<?php

namespace DNAFactory\FakeConfigurable\Model\ProductLink\CollectionProvider;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductLink\CollectionProviderInterface;

class Brother implements CollectionProviderInterface
{
    /** @var \DNAFactory\FakeConfigurable\Model\Brother */
    protected $model;

    /**
     * Accessory constructor.
     * @param \DNAFactory\FakeConfigurable\Model\Brother $model
     */
    public function __construct(
        \DNAFactory\FakeConfigurable\Model\Brother $model
    ) {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(Product $product)
    {
        return (array) $this->model->getBrotherProducts($product, true);
    }
}
