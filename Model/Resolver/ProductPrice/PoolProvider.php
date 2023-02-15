<?php
declare(strict_types=1);
namespace Bss\ApiCustomizeForGranato\Model\Resolver\ProductPrice;

use Magento\Framework\Exception\LocalizedException;

class PoolProvider
{
    private array $pools;

    /**
     * @param PriceProviderInterface[] $pools
     */
    public function __construct(
        array $pools
    ) {
        $this->pools = $pools;
    }

    /**
     * Get field provider
     *
     * @param string $name
     * @return PriceProviderInterface
     * @throws LocalizedException
     */
    public function getProvider(string $name): PriceProviderInterface
    {
        if (isset($this->pools[$name])) {
            return $this->pools[$name];
        }

        throw new LocalizedException(__("Not support field provider"));
    }
}
