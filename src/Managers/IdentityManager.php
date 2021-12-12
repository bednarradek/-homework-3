<?php

declare(strict_types=1);

namespace App\Managers;

use App\Orm\Entities\CartEntity;
use App\Orm\Entities\CartProductEntity;
use App\Orm\Repositories\CartsProductsRepository;
use App\Orm\Repositories\CartsRepository;
use App\Orm\Repositories\CustomersRepository;

class IdentityManager
{
    private ?CartEntity $cartEntity = null;

    /** @var CartProductEntity[]|null */
    private ?array $cartsProducts = null;

    private CartsRepository $cartsRepository;
    private CustomersRepository $customersRepository;
    private CartsProductsRepository $cartsProductsRepository;

    public function __construct(
        CartsRepository $cartsRepository,
        CustomersRepository $customersRepository,
        CartsProductsRepository $cartsProductsRepository
    ) {
        $this->cartsRepository = $cartsRepository;
        $this->customersRepository = $customersRepository;
        $this->cartsProductsRepository = $cartsProductsRepository;
    }

    public function setupCart(string $customerId): void
    {
        $cart = $this->cartsRepository->findFirst(['customers.id' => $customerId]);

        if (!$cart) {
            $customer = $this->customersRepository->findById($customerId);
            $cart = new CartEntity($this->cartsRepository);
            $cart->setCustomer($customer);
            $cart->save();
        }

        $this->cartsProducts = $this->cartsProductsRepository->find(['carts_id' => $cart->getId()]);

        $this->cartEntity = $cart;
    }

    public function getCart(): ?CartEntity
    {
        return $this->cartEntity;
    }

    /**
     * @return CartProductEntity[]|null
     */
    public function getCartProducts(): ?array
    {
        return $this->cartsProducts;
    }
}
