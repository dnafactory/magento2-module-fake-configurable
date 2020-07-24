<?php

namespace DNAFactory\FakeConfigurable\Helper\Config;

use DNAFactory\FakeConfigurable\Api\FakeConfigurableConfigurationInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class FakeConfigurableConfiguration extends AbstractHelper implements FakeConfigurableConfigurationInterface
{
    const XML_FAKECONFIGURABLE_GENERAL_BROTHERLABEL = 'fakeconfigurable/general/brother_label';
    const XML_DUMMY_FOO2 = 'dummy/bar/foo2';

    protected $storeManager;
    protected $configWriter;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        WriterInterface $configWriter
    ) {
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
        parent::__construct($context);
    }

    public function getBrotherLabel($scopeConfig = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(self::XML_FAKECONFIGURABLE_GENERAL_BROTHERLABEL, $scopeConfig, $scopeCode);
    }
}
