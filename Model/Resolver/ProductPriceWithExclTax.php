<?php
declare(strict_types=1);
namespace Bss\ApiCustomizeForGranato\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\Context;
use Magento\Tax\Model\Config;
use Bss\ApiCustomizeForGranato\Model\Resolver\ProductPrice\PoolProvider as PriceProviderPool;

class ProductPriceWithExclTax implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private Config $taxConfig;
    private PriceProviderPool $poolProvider;

    /**
     * @param Config $taxConfig
     * @param PriceProviderPool $poolProvider
     */
    public function __construct(
        Config $taxConfig,
        PriceProviderPool $poolProvider
    ) {
        $this->taxConfig = $taxConfig;
        $this->poolProvider = $poolProvider;
    }

    /**
     * @param Field $field
     * @param Context $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $priceData = $value[$field->getName()];
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $priceData['model'] ?? null;
        if (!$product) {
            return $priceData;
        }

        $store = $context->getExtensionAttributes()->getStore();
        if ($this->taxConfig->getPriceDisplayType($store->getCode()) === Config::DISPLAY_TYPE_BOTH && $this->canShowPrice($product)) {
            $provider = $this->poolProvider->getProvider($field->getName());
            $provider->provideField($product, $store, $priceData);
        }

        return $priceData;
    }

    /**
     * Check if the product is allowed to show price
     *
     * @param ProductInterface $product
     * @return bool
     */
    private function canShowPrice($product): bool
    {
        if ($product->hasData('can_show_price') && $product->getData('can_show_price') === false) {
            return false;
        }

        return true;
    }
}
