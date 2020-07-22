<?php

namespace DNAFactory\FakeConfigurable\Model\Product\Brother;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\CopyConstructorInterface;
use Magento\Catalog\Model\Product\Link;

class CopyConstructor implements CopyConstructorInterface
{
    /**
     * Build product links
     *
     * @param Product $product
     * @param Product $duplicate
     * @return void
     */
    public function build(Product $product, Product $duplicate)
    {
        $data = [];
        $attributes = [];

        $link = $product->getLinkInstance();
        $link->useBrotherLinks();
        foreach ($link->getAttributes() as $attribute) {
            if (isset($attribute['code'])) {
                $attributes[] = $attribute['code'];
            }
        }
        /** @var Link $link  */
        foreach ($product->getBrotherLinkCollection() as $link) {
            $data[$link->getLinkedProductId()] = $link->toArray($attributes);
        }

        $duplicate->setBrotherLinkData($data);
    }
}
