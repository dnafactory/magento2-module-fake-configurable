#Fake Configurable

`composer require dnafactory/fake-configurable`

Questo modulo crea finti configurabili (associabili a qualsiasi tipologia di prodotti). 
In questo modo è possibile creare url differenti per prodotti e beneficiare visivamente delle configurazioni.

## Facade & Usage

All associated product are called "brother"

```php
<?php

namespace DNAFactory\FakeConfigurable\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Collection as LinkCollection;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection as ProductCollection;

interface BrotherManagementInterface
{
    public function getBrotherProducts(ProductInterface $product): array;
    public function getBrotherProductIds(ProductInterface $product): array;
    public function getBrotherProductCollection(ProductInterface $product): ProductCollection;
    public function getBrotherLinkCollection(ProductInterface $product): LinkCollection;
}
```



