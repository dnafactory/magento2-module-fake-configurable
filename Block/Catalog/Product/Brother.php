<?php

namespace DNAFactory\FakeConfigurable\Block\Catalog\Product;

use DNAFactory\FakeConfigurable\Api\BrotherManagementInterface;
use DNAFactory\FakeConfigurable\Api\FakeConfigurableConfigurationInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;

class Brother extends \Magento\Framework\View\Element\Template
{
    public const AJAX_URL = 'fakeconfigurable/product/getBrothers';
    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;
    /**
     * @var ProductInterface
     */
    private $product = null;
    /**
     * @var BrotherManagementInterface
     */
    private $brotherManagement;
    /**
     * @var FakeConfigurableConfigurationInterface
     */
    private $fakeConfigurableConfiguration;
    /**
     * @var Json
     */
    private $jsonSerializer;

    public function __construct(
        Template\Context $context,
        BrotherManagementInterface $brotherManagement,
        FakeConfigurableConfigurationInterface $fakeConfigurableConfiguration,
        Json $jsonSerializer,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->brotherManagement = $brotherManagement;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->fakeConfigurableConfiguration = $fakeConfigurableConfiguration;
        $this->jsonSerializer = $jsonSerializer;
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

    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl(self::AJAX_URL);
    }

    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        $product = $this->getProduct();
        $this->jsLayout['components']['fakeConfigurable']['productId'] = $product->getRowId() ?? $product->getId();
        $this->jsLayout['components']['fakeConfigurable']['brotherLabel'] = $this->fakeConfigurableConfiguration->getBrotherLabel();
        return $this->jsonSerializer->serialize($this->jsLayout);
    }

    public function includeCurrentProduct()
    {
        return $this->fakeConfigurableConfiguration->includeCurrentProduct();
    }
}
