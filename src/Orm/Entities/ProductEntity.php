<?php

declare(strict_types=1);

namespace App\Orm\Entities;

class ProductEntity extends BaseEntity
{
    private ?string $name;
    private ?string $description;
    private ?float $price;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice()
        ]);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
