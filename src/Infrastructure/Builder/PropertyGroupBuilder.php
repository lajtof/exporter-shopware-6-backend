<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\PropertyGroupMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;

class PropertyGroupBuilder
{
    /**
     * @var PropertyGroupMapperInterface[]
     */
    private array $collection;

    public function __construct(PropertyGroupMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        Shopware6PropertyGroup $shopware6PropertyGroup,
        AbstractAttribute $attribute,
        ?Language $language = null,
        ?Shopware6Language $shopware6Language = null
    ): Shopware6PropertyGroup {

        foreach ($this->collection as $mapper) {
            $shopware6PropertyGroup = $mapper->map(
                $channel,
                $export,
                $shopware6PropertyGroup,
                $attribute,
                $language,
                $shopware6Language
            );
        }

        return $shopware6PropertyGroup;
    }
}
