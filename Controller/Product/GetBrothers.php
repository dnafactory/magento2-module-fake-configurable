<?php

namespace DNAFactory\FakeConfigurable\Controller\Product;

use DNAFactory\FakeConfigurable\Api\BrotherManagementInterface;
use DNAFactory\FakeConfigurable\Api\FakeConfigurableConfigurationInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media as SwatchMedia;

class GetBrothers extends \Magento\Framework\App\Action\Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var BrotherManagementInterface
     */
    protected $brotherManagement;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;
    /**
     * @var FakeConfigurableConfigurationInterface
     */
    protected $fakeConfigurableConfiguration;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    protected SwatchData $swatchesData;
    protected SwatchMedia $swatchesMedia;

    public function __construct(
        SwatchMedia $swatchesMedia,
        SwatchData $swatchesData,
        JsonFactory $jsonResultFactory,
        ProductRepositoryInterface $productRepository,
        BrotherManagementInterface $brotherManagement,
        \Magento\Catalog\Helper\Image $imageHelper,
        FakeConfigurableConfigurationInterface $fakeConfigurableConfiguration,
        LoggerInterface $logger,
        Context $context
    ) {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productRepository = $productRepository;
        $this->brotherManagement = $brotherManagement;
        $this->imageHelper = $imageHelper;
        $this->fakeConfigurableConfiguration = $fakeConfigurableConfiguration;
        $this->logger = $logger;
        $this->swatchesData = $swatchesData;
        $this->swatchesMedia = $swatchesMedia;
    }

    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        try {
            $productId = $this->getRequest()->getParam('productId');
            if (!$productId) {
                $result->setData($this->makeData(-1, __("ProductID are emptpy")));
                return $result;
            }

            $product = $this->productRepository->getById($productId);
            if (!$product) {
                $result->setData($this->makeData(-1, __("Product not found")));
                return $result;
            }

            $brothers = $this->brotherManagement->getBrotherProducts($product);
            if ($this->fakeConfigurableConfiguration->includeCurrentProduct()) {
                $product->setData("class", "active");
                array_unshift($brothers , $product);
            }

            $result->setData($this->makeData(0, "", $this->convertToArray($brothers)));
        } catch (\Exception $e) {
            $result->setData($this->makeData(-1, $e->getMessage()));
            return $result;
        }

        return $result;
    }

    private function convertToArray($brothers)
    {
        $tmp = [];
        foreach ($brothers as $brother) {
            //Better performance
            //$brotherAsArray = $brother->toArray();
            $brotherAsArray = [];

            if ($brother->hasClass()) {
                $brotherAsArray['class'] = $brother->getClass();
            }

            $brotherAttribute = $this->fakeConfigurableConfiguration->getBrotherAttribute();
            $useAttributeImage = $this->fakeConfigurableConfiguration->useBrotherAttributeImage();
            if ($brotherAttribute) {
                $brotherAsArray["attribute"] = $this->getAttributeValue($brother, $brotherAttribute);
            }

            if ($brotherAttribute &&
                $useAttributeImage &&
                $brother->getData($brotherAttribute)) {

                try {
                    $brotherAsArray['image_url'] = $this->getImageByBrotherAttribute($brother, $brotherAttribute);
                } catch (\Exception $e) {
                    $brotherAsArray['image_url'] = $this->getImageByBrother($brother);
                }

            } else {
                $brotherAsArray['image_url'] = $this->getImageByBrother($brother);
            }

            $brotherAsArray['product_url'] = $brother->getProductUrl();
            $tmp[] = $brotherAsArray;
        }

        return $tmp;
    }

    private function makeData($status = -1, $error = '', $data = [])
    {
        return [
            'status' => $status,
            'error' => $error,
            'data' => $data
        ];
    }

    protected function getAttributeValue($resourceItem, $field)
    {
        try {
            $attribute = $resourceItem->getResource()->getAttribute($field);
            if ($attribute->usesSource()) {
                return $attribute->getSource()->getOptionText($resourceItem[$field]);
            }

            return $resourceItem[$field];
        } catch (\Exception $e) {
            $this->logger->error('Getting Attribute Value Error', ['error' => $e->getMessage()]);
            return "";
        }
    }

    protected function getImageByBrotherAttribute($brother, $attribute)
    {
        $optionId = $brother->getData($attribute);
        $swatches = $this->swatchesData->getSwatchesByOptionsId([$optionId]);

        if (empty($swatches)) {
            throw new \Exception(__("No swatches found for option_id: %1", $optionId));
        }
        $swatch = array_pop($swatches);

        if (!array_key_exists("type", $swatch) ||
            !array_key_exists("value", $swatch) ||
            $swatch["type"] != \Magento\Swatches\Model\Swatch::SWATCH_TYPE_VISUAL_IMAGE) {
            throw new \Exception(__("Swatch with option_id: %1 is not an image", $optionId));
        }
        $imagePath = $swatch["value"];
        return $this->swatchesMedia->getSwatchAttributeImage('swatch_thumb', $imagePath);
    }

    protected function getImageByBrother($brother)
    {
        $this->imageHelper->init($brother, 'product_base_image');
        return $this->imageHelper->getUrl();
    }
}
