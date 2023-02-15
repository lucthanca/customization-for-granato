<?php
declare(strict_types=1);
namespace Bss\ApiCustomizeForGranato\Model\Resolver\ProductPrice;

use Magento\Framework\Pricing\SaleableInterface;
use Magento\Store\Api\Data\StoreInterface;

class MaximumPriceExclTaxField extends MinimumPriceExclTaxField implements PriceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function provideField(SaleableInterface $product, StoreInterface $store, &$priceData)
    {
        $provider = $this->poolProvider->getProviderByProductType($product->getTypeId());
        $regularPriceExclT = $provider->getMaximalRegularPrice($product)->getBaseAmount();
        $finalPriceExclT = $provider->getMaximalFinalPrice($product)->getBaseAmount();

        $priceData['regular_price_excl_tax'] = $this->formatPriceOutput($regularPriceExclT, $store);
        $priceData['final_price_excl_tax'] = $this->formatPriceOutput($finalPriceExclT, $store);
    }
}
