<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

abstract class AbstractProductPropertyGroupMapper implements ProductMapperInterface
{
    protected AttributeRepositoryInterface $repository;

    protected Shopware6PropertyGroupOptionClient $propertyGroupOptionClient;

    protected TranslationInheritanceCalculator $calculator;

    public function __construct(
        AttributeRepositoryInterface $repository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient,
        TranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
        $this->calculator = $calculator;
    }

    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        foreach ($channel->getPropertyGroup() as $attributeId) {
            $this->attributeMap($shopware6Product, $attributeId, $product, $channel, $language);
        }

        return $shopware6Product;
    }

    abstract public function getType(): string;

    protected function isSupported(string $type): bool
    {
        return $this->getType() === $type;
    }

    abstract protected function addProperty(
        Shopware6Product $shopware6Product,
        AbstractAttribute $attribute,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product;

    private function attributeMap(
        Shopware6Product $shopware6Product,
        AttributeId $attributeId,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute,sprintf('Expected a value other than null for property attribute %s', $attributeId->getValue()));

        if (false === $product->hasAttribute($attribute->getCode())) {
            return $shopware6Product;
        }

        if ($this->isSupported($attribute->getType())) {
            $this->addProperty($shopware6Product, $attribute, $product, $channel, $language);
        }

        return $shopware6Product;
    }
}
