<?php

namespace DNAFactory\FakeConfigurable\Management;

use DNAFactory\FakeConfigurable\Api\BrotherManagementInterface;
use DNAFactory\FakeConfigurable\Model\Brother;
use DNAFactory\FakeConfigurable\Model\BrotherFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Collection as LinkCollection;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection as ProductCollection;

class BrotherManagement implements BrotherManagementInterface
{
    /**
     * @var BrotherFactory
     */
    protected $brotherFactory;
    /**
     * @var Brother[]
     */
    protected $brothers = [];

    public function __construct(
        BrotherFactory $brotherFactory
    ) {
        $this->brotherFactory = $brotherFactory;
    }

    public function getBrotherProducts(ProductInterface $product): array
    {
        $brother = $this->getBrother($product);
        return $brother->getBrotherProducts($product);
    }

    public function getBrotherProductIds(ProductInterface $product): array
    {
        $brother = $this->getBrother($product);
        return $brother->getBrotherProductIds($product);
    }

    public function getBrotherProductCollection(ProductInterface $product): ProductCollection
    {
        $brother = $this->getBrother($product);
        return $brother->getBrotherProductCollection($product);
    }

    public function getBrotherLinkCollection(ProductInterface $product): LinkCollection
    {
        $brother = $this->getBrother($product);
        return $brother->getBrotherLinkCollection($product);
    }

    /**
     * @param ProductInterface $product
     * @return Brother
     */
    protected function getBrother(ProductInterface $product)
    {
        if (!isset($this->brothers[$product->getId()])) {
            $this->brothers[$product->getId()] = $this->brotherFactory->create();
        }
        return $this->brothers[$product->getId()];
    }
}
