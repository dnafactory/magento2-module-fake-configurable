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
    const XML_FAKECONFIGURABLE_GENERAL_BROTHERATTRIBUTE = 'fakeconfigurable/general/brother_attribute';
    const XML_FAKECONFIGURABLE_GENERAL_BROTHER_INCLUDECURRENTPRODUCT = 'fakeconfigurable/general/include_current_product';

    public function getBrotherLabel($scopeConfig = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->getConfig(self::XML_FAKECONFIGURABLE_GENERAL_BROTHERLABEL, $scopeConfig, $scopeCode);
    }

    public function getBrotherAttribute($scopeConfig = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->getConfig(self::XML_FAKECONFIGURABLE_GENERAL_BROTHERATTRIBUTE, $scopeConfig, $scopeCode);
    }

    public function includeCurrentProduct($scopeConfig = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return (bool)$this->getConfig(self::XML_FAKECONFIGURABLE_GENERAL_BROTHER_INCLUDECURRENTPRODUCT, $scopeConfig, $scopeCode);
    }

    protected function getConfig($config, $scopeConfig = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue($config, $scopeConfig, $scopeCode);
    }
}
