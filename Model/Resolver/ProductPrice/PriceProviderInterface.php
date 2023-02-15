<?php
declare(strict_types=1);
namespace Bss\ApiCustomizeForGranato\Model\Resolver\ProductPrice;

use Magento\Framework\Pricing\SaleableInterface;
use Magento\Store\Api\Data\StoreInterface;

interface PriceProviderInterface
{
    /**
     * @param SaleableInterface $product
     * @param StoreInterface $store
     * @param array $priceData
     * @return void
     */
    public function provideField(SaleableInterface $product, StoreInterface $store, &$priceData);
}
