<?php

namespace DNAFactory\FakeConfigurable\Plugin\CatalogImportExport\Model\Import;

use Magento\CatalogImportExport\Model\Import\Product as ProductImportExport;
use DNAFactory\FakeConfigurable\Model\Product\Brother\Link;

class Product
{
    /**
     * @param  ProductImportExport  $subject
     * @param $result
     * @return mixed
     */
    public function afterGetLinkNameToId(ProductImportExport $subject, $result)
    {
        $result['_brother_'] = Link::LINK_TYPE_BROTHER;
        return $result;
    }
}
