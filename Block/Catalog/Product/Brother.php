<?php

namespace DNAFactory\FakeConfigurable\Block\Catalog\Product;

use DNAFactory\FakeConfigurable\Api\BrotherManagementInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Template;

class Brother extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ProductInterface
     */
    private $product = null;
    /**
     * @var BrotherManagementInterface
     */
    private $brotherManagement;

    public function __construct(
        Template\Context $context,
        BrotherManagementInterface $brotherManagement,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->brotherManagement = $brotherManagement;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->getData('current_product')->getProduct();
        }
        return $this->product;
    }

    public function getBrothers()
    {
        $product = $this->getProduct();
        return $this->brotherManagement->getBrotherProducts($product);
    }
}
