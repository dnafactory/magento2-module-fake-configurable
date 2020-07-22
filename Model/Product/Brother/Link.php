<?php

namespace DNAFactory\FakeConfigurable\Model\Product\Brother;

class Link extends \Magento\Catalog\Model\Product\Link
{
    const LINK_TYPE_BROTHER = 333;

    /**
     * @return $this
     */
    public function useBrotherLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_BROTHER);
        return $this;
    }
}
