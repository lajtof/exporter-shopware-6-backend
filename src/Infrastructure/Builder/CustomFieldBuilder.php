<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\BatchAction\Application\Form\BatchActionFormInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;
use Webmozart\Assert\Assert;

class CustomFieldBuilder
{
    /**
     * @var CustomFieldMapperInterface[]
     */
    private iterable $collection;

    public function __construct(iterable $collection)
    {
        Assert::allIsInstanceOf($collection, CustomFieldMapperInterface::class);
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): AbstractShopware6CustomField {

        foreach ($this->collection as $mapper) {
            $shopware6CustomField = $mapper->map($channel, $export, $shopware6CustomField, $attribute, $language);
        }

        return $shopware6CustomField;
    }
}
