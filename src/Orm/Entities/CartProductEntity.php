<?php

declare(strict_types=1);

namespace App\Orm\Entities;

class CartProductEntity extends BaseEntity
{
    private ?CartEntity $cart = null;
    private ?ProductEntity $product = null;
    private ?int $amount = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'product' => $this->product->toArray(),
                'amount' => $this->getAmount()
            ]
        );
    }

    public function getCart(): ?CartEntity
    {
        return $this->cart;
    }

    public function setCart(?CartEntity $cart): void
    {
        $this->cart = $cart;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }
}
