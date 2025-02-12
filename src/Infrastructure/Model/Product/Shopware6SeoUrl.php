<?php
declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

class Shopware6SeoUrl implements \JsonSerializable
{
    private ?string $id;

    private string $seoPathInfo;

    private string $salesChannelId;

    private string $pathInfo;

    private string $routeName;

    private ?bool $isCanonical;

    private bool $isModified;

    private string $languageId;

    public function __construct(
        ?string $id = null,
        string $seoPathInfo,
        string $salesChannelId,
        string $pathInfo,
        string $routeName,
        string $languageId,
        ?bool $isCanonical,
        bool $isModified
    ) {
        $this->id = $id;
        $this->seoPathInfo = $seoPathInfo;
        $this->salesChannelId = $salesChannelId;
        $this->pathInfo = $pathInfo;
        $this->routeName = $routeName;
        $this->isCanonical = $isCanonical;
        $this->isModified = $isModified;
        $this->languageId = $languageId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->id,
            'salesChannelId' => $this->salesChannelId,
            'seoPathInfo' => $this->seoPathInfo,
            'pathInfo' => $this->pathInfo,
            'routeName' => $this->routeName,
            'isCanonical' => $this->isCanonical,
            'isModified' => $this->isModified,
            'languageId' => $this->languageId
        ];

        if ($this->id) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    public function getSeoPathInfo(): string
    {
        return $this->seoPathInfo;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function isCanonical(): ?bool
    {
        return $this->isCanonical;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }
}
