<?php

namespace DNAFactory\FakeConfigurable\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Collection;
use Magento\Framework\DataObject;
use DNAFactory\FakeConfigurable\Model\Product\Brother\Link;
use Magento\Store\Model\StoreManagerInterface;

class Brother extends DataObject
{

    /**
     * FlyWeight var
     *
     * @var array
     */
    protected $botherProductIds = [];

    /**
     * FlyWeight var
     *
     * @var array
     */
    protected $botherProducts = [];

    /**
     * Product link instance
     *
     * @var Link
     */
    protected $linkInstance;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    protected Product\Visibility $catalogProductVisibility;

    /**
     * Brother constructor.
     * @param StoreManagerInterface $storeManager
     * @param Link $productLink
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Link $productLink,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    ) {
        parent::__construct($data);
        $this->linkInstance = $productLink;
        $this->storeManager = $storeManager;
        $this->catalogProductVisibility = $catalogProductVisibility;
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
    public function getBrotherProducts(ProductInterface $currentProduct, $includeDisabled = false)
    {
        $productId = $currentProduct->getRowId() ?? $currentProduct->getId();
        if (!$this->hasBrotherProducts($productId)) {
            $products = [];
            $collection = $this->getGeneralBrotherProductCollection($currentProduct);
            if (!$includeDisabled) {
                $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
            }
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->botherProducts[$productId] = $products;
        }
        return $this->botherProducts[$productId];
    }

    /**
     * Retrieve Brother products identifiers
     *
     * @param ProductInterface $currentProduct
     * @return array
     */
    public function getBrotherProductIds(ProductInterface $currentProduct)
    {
        $productId = $currentProduct->getRowId() ?? $currentProduct->getId();
        if (!$this->hasBrotherProductIds($productId)) {
            $ids = [];
            foreach ($this->getBrotherProducts($currentProduct) as $product) {
                $ids[] = $product->getRowId() ?? $product->getId();
            }
            $this->botherProductIds[$productId] = $ids;
        }
        return $this->botherProductIds[$productId];
    }

    protected function getGeneralBrotherProductCollection(ProductInterface $currentProduct)
    {
        $collection = $this->getLinkInstance()
            ->useBrotherLinks()
            ->getProductCollection()
            ->addStoreFilter($this->storeManager->getStore())
            ->addAttributeToSelect('*')
            ->setIsStrongMode();
        $collection->setProduct($currentProduct);
        return $collection;
    }

    /**
     * Retrieve collection Brother product
     *
     * @param ProductInterface $currentProduct
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getBrotherProductCollection(ProductInterface $currentProduct)
    {
        $collection = $this->getGeneralBrotherProductCollection($currentProduct)
            ->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
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

    /**
     * Retrieve true if flyweight is set for the product id
     *
     * @param string $productId
     * @return Bool
     */
    protected function hasBrotherProductIds($productId)
    {
        if (!array_key_exists($productId, $this->botherProductIds) ||
            !isset($this->botherProductIds[$productId])) {
            return false;
        }
        return true;
    }


    /**
     * Retrieve true if flyweight is set for the product id
     *
     * @param string $productId
     * @return Bool
     */
    protected function hasBrotherProducts($productId)
    {
        if (!array_key_exists($productId, $this->botherProducts) ||
            !isset($this->botherProductIds[$productId])) {
            return false;
        }
        return true;
    }

}
