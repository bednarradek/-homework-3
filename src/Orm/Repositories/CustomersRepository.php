<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\CustomerEntity;
use App\Orm\Entities\IEntity;

/**
 * @method CustomerEntity[] findAll()
 * @method CustomerEntity[] find(array $where)
 * @method CustomerEntity|null findFirst(array $where)
 * @method CustomerEntity|null findById(string $id)
 */
class CustomersRepository extends BaseRepository
{
    protected string $tableName = 'customers';

    protected function prepareEntity(array $data): CustomerEntity
    {
        $entity = new CustomerEntity($this);

        $entity->setId($data['id']);
        $entity->setFirstName($data['first_name']);
        $entity->setLastName($data['last_name']);
        $entity->setEmail($data['email']);
        $this->prepareDateColumns($entity, $data);

        return $entity;
    }

    /**
     * @param CustomerEntity $entity
     * @return array<string, mixed>
     */
    protected function prepareToSave(IEntity $entity): array
    {
        return [
            'first_name' => $entity->getFirstName(),
            'last_name' => $entity->getLastName(),
            'email' => $entity->getEmail()
        ];
    }
}
