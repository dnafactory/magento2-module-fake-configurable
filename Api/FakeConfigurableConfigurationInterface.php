<?php

namespace DNAFactory\FakeConfigurable\Api;

interface FakeConfigurableConfigurationInterface
{
    public function getBrotherLabel();
    public function getBrotherAttribute();
    public function includeCurrentProduct();
    public function useBrotherAttributeImage();
}
