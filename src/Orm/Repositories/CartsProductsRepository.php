<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\CartProductEntity;
use App\Orm\Entities\IEntity;
use Nette\Database\Explorer;

/**
 * @method CartProductEntity[] findAll()
 * @method CartProductEntity[] find(array $where)
 * @method CartProductEntity|null findFirst(array $where)
 * @method CartProductEntity|null findById(string $id)
 */
class CartsProductsRepository extends BaseRepository
{
    protected string $tableName = 'carts_products';
    protected ProductsRepository $productsRepository;
    protected CartsRepository $cartsRepository;

    public function __construct(
        Explorer $explorer,
        ProductsRepository $productsRepository,
        CartsRepository $cartsRepository
    ) {
        parent::__construct($explorer);
        $this->productsRepository = $productsRepository;
        $this->cartsRepository = $cartsRepository;
    }

    protected function prepareEntity(array $data): IEntity
    {
        $entity = new CartProductEntity($this);

        $entity->setId($data['id']);
        $entity->setCart($this->cartsRepository->findById($data['carts_id']));
        $entity->setProduct($this->productsRepository->findById($data['products_id']));
        $entity->setAmount($data['amount']);
        $this->prepareDateColumns($entity, $data);

        return $entity;
    }

    /**
     * @param CartProductEntity $entity
     * @return array<string, mixed>
     */
    protected function prepareToSave(IEntity $entity): array
    {
        return [
            'carts_id' => $entity->getCart()->getId(),
            'products_id' => $entity->getProduct()->getId(),
            'amount' => $entity->getAmount()
        ];
    }
}
