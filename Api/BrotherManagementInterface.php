<?php

namespace DNAFactory\FakeConfigurable\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Collection as LinkCollection;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection as ProductCollection;

interface BrotherManagementInterface
{
    public function getBrotherProducts(ProductInterface $product): array;
    public function getBrotherProductIds(ProductInterface $product): array;
    public function getBrotherProductCollection(ProductInterface $product): ProductCollection;
    public function getBrotherLinkCollection(ProductInterface $product): LinkCollection;
}
