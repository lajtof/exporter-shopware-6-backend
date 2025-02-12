<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

class Shopware6QueryBuilder
{
    public const EQUALS = 'equals';

    /**
     * @var array
     */
    private array $parts = [];

    private ?int $limit = null;

    private ?int $page = null;

    private array $includes = [];

    private array $associations = [];

    public function limit(int $limit): Shopware6QueryBuilder
    {
        $this->limit = $limit;

        return $this;
    }

    public function setPage(int $page): Shopware6QueryBuilder
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return $this
     */
    public function add(string $type, string $field, string $value): Shopware6QueryBuilder
    {
        $this->parts[] = [
            'type' => $type,
            'field' => $field,
            'value' => $value,
        ];

        return $this;
    }

    public function equals(string $field, string $value): Shopware6QueryBuilder
    {
        return $this->add(self::EQUALS, $field, $value);
    }

    /**
     * @return $this
     */
    public function sort(string $field, string $value): Shopware6QueryBuilder
    {
        $this->parts[0]['sort'] =
            [
                'field' => $field,
                'order' => $value,
            ];

        return $this;
    }

    public function getQuery(): string
    {
        $param['filter'] = [];
        if (count($this->parts) > 0) {
            $param['filter'] = $this->parts;
        }

        if ($this->isLimit()) {
            $param['limit'] = $this->limit;

            if ($this->page > 0) {
                $param['page'] = $this->page;
            }
        }

        if (count($this->includes) > 0) {
            $param['includes'] = $this->includes;
        }

        if (count($this->associations) > 0) {
            $param['associations'] = $this->associations;
        }

        return http_build_query($param);
    }

    private function isLimit(): bool
    {
        return null !== $this->limit;
    }

    public function include(string $entityName, array $fields)
    {
        $this->includes[$entityName] = $fields;
    }

    public function association(string $entityName, array $fields): Shopware6QueryBuilder
    {
        $this->associations[$entityName] = $fields;
        return $this;
    }
}
