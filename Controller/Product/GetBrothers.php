<?php

namespace DNAFactory\FakeConfigurable\Controller\Product;

use DNAFactory\FakeConfigurable\Api\BrotherManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

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

    public function __construct(
        JsonFactory $jsonResultFactory,
        ProductRepositoryInterface $productRepository,
        BrotherManagementInterface $brotherManagement,
        \Magento\Catalog\Helper\Image $imageHelper,
        Context $context
    ) {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productRepository = $productRepository;
        $this->brotherManagement = $brotherManagement;
        $this->imageHelper = $imageHelper;
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
            //$brotherAsArray = $brother->toArray();
            $brotherAsArray = [];

            $brotherAsArray['product_url'] = $brother->getProductUrl();

            $this->imageHelper->init($brother, 'product_base_image');
            $brotherAsArray['image_url'] = $this->imageHelper->getUrl();

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
}
