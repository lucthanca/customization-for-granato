<?php
declare(strict_types=1);
namespace Bss\ApiCustomizeForGranato\Model\Resolver\ProductPrice;

use Magento\CatalogGraphQl\Model\Resolver\Product\Price\ProviderPool as PriceProviderPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Store\Api\Data\StoreInterface;

class MinimumPriceExclTaxField implements PriceProviderInterface
{
    protected PriceProviderPool $poolProvider;

    /**
     * @param PriceProviderPool $poolProvider
     */
    public function __construct(
        PriceProviderPool $poolProvider
    ) {
        $this->poolProvider = $poolProvider;
    }

    /**
     * @param SaleableInterface $product
     * @param StoreInterface $store
     * @param array $priceData
     * @return void
     */
    public function provideField(SaleableInterface $product, StoreInterface $store, &$priceData)
    {
        $provider = $this->poolProvider->getProviderByProductType($product->getTypeId());
        $regularPriceExclT = $provider->getMinimalRegularPrice($product)->getBaseAmount();
        $finalPriceExclT = $provider->getMinimalFinalPrice($product)->getBaseAmount();

        $priceData['regular_price_excl_tax'] = $this->formatPriceOutput($regularPriceExclT, $store);
        $priceData['final_price_excl_tax'] = $this->formatPriceOutput($finalPriceExclT, $store);
    }

    /**
     * Format for output field
     *
     * @param float $value
     * @param StoreInterface $store
     * @return array
     */
    protected function formatPriceOutput($value, StoreInterface $store): array
    {
        return [
            "value" => $value,
            "currency" => $store->getCurrentCurrencyCode()
        ];
    }
}
