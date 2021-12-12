<?php

declare(strict_types=1);

namespace App\Orm\Entities;

class CartEntity extends BaseEntity
{
    private ?CustomerEntity $customer = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            ['customer' => $this->customer->toArray()]
        );
    }

    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }
}
