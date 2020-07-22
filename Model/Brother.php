<?php

namespace DNAFactory\FakeConfigurable\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Collection;
use Magento\Framework\DataObject;
use DNAFactory\FakeConfigurable\Model\Product\Brother\Link;

class Brother extends DataObject
{
    /**
     * Product link instance
     *
     * @var Link
     */
    protected $linkInstance;

    /**
     * Brother constructor.
     * @param Link $productLink
     * @param array $data
     */
    public function __construct(Link $productLink, array $data = [])
    {
        parent::__construct($data);
        $this->linkInstance = $productLink;
    }

    /**
     * Retrieve link instance
     *
     * @return Link
     */
    public function getLinkInstance()
    {
        return $this->linkInstance;
    }

    /**
     * Retrieve array of Brother products
     *
     * @param ProductInterface $currentProduct
     * @return array
     */
    public function getBrotherProducts(ProductInterface $currentProduct)
    {
        if (!$this->hasBrotherProducts()) {
            $products = [];
            $collection = $this->getBrotherProductCollection($currentProduct);
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setBrotherProducts($products);
        }
        return $this->getData('brother_products');
    }

    /**
     * Retrieve Brother products identifiers
     *
     * @param ProductInterface $currentProduct
     * @return array
     */
    public function getBrotherProductIds(ProductInterface $currentProduct)
    {
        if (!$this->hasBrotherProductIds()) {
            $ids = [];
            foreach ($this->getBrotherProducts($currentProduct) as $product) {
                $ids[] = $product->getId();
            }
            $this->setBrotherProductIds($ids);
        }
        return $this->getData('brother_product_ids');
    }

    /**
     * Retrieve collection Brother product
     *
     * @param ProductInterface $currentProduct
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getBrotherProductCollection(ProductInterface $currentProduct)
    {
        $collection = $this->getLinkInstance()
                            ->useBrotherLinks()
                            ->getProductCollection()
                            ->addAttributeToSelect('*')
                            ->setIsStrongMode();
        $collection->setProduct($currentProduct);
        return $collection;
    }

    /**
     * Retrieve collection Brother link
     *
     * @param ProductInterface $currentProduct
     * @return Collection
     */
    public function getBrotherLinkCollection(ProductInterface $currentProduct)
    {
        $collection = $this->getLinkInstance()->useBrotherLinks()->getLinkCollection();
        $collection->setProduct($currentProduct);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }
}
