<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\IEntity;
use App\Orm\Entities\ProductEntity;

/**
 * @method ProductEntity[] findAll()
 * @method ProductEntity[] find(array $where)
 * @method ProductEntity|null findFirst(array $where)
 * @method ProductEntity|null findById(string $id)
 */
class ProductsRepository extends BaseRepository
{
    protected string $tableName = 'products';

    protected function prepareEntity(array $data): IEntity
    {
        $entity = new ProductEntity($this);

        $entity->setId($data['id']);
        $entity->setName($data['name']);
        $entity->setDescription($data['description']);
        $entity->setPrice($data['price']);
        $this->prepareDateColumns($entity, $data);

        return $entity;
    }

    /**
     * @param ProductEntity $entity
     * @return array<string, mixed>
     */
    protected function prepareToSave(IEntity $entity): array
    {
        return [
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
            'price' => $entity->getPrice()
        ];
    }
}
