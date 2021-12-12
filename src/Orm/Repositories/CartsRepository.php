<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\CartEntity;
use App\Orm\Entities\IEntity;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\SqlLiteral;

/**
 * @method CartEntity[] findAll()
 * @method CartEntity[] find(array $where)
 * @method CartEntity|null findFirst(array $where)
 * @method CartEntity|null findById(string $id)
 */
class CartsRepository extends BaseRepository
{
    protected string $tableName = 'carts';
    protected CustomersRepository $customersRepository;

    public function __construct(
        Explorer $explorer,
        CustomersRepository $customersRepository
    ) {
        parent::__construct($explorer);
        $this->customersRepository = $customersRepository;
    }

    protected function prepareEntity(array $data): CartEntity
    {
        $entity = new CartEntity($this);

        $entity->setId($data['id']);
        $entity->setCustomer($this->customersRepository->findById($data['customers_id']));
        $this->prepareDateColumns($entity, $data);

        return $entity;
    }

    /**
     * @param int $days
     * @return CartEntity[]
     */
    public function findLeftCarts(int $days): array
    {
        $data = $this->explorer->query(
            "
            SELECT 
            carts.id AS id,
            carts.customers_id AS customers_id,
            carts.updated AS updated,
            carts.created AS created
            FROM carts
            INNER JOIN carts_products ON carts_products.carts_id = carts.id
            WHERE 
                carts.updated IS NOT NULL AND carts.updated <= CURRENT_TIMESTAMP - INTERVAL ?
            GROUP BY carts.id, carts.customers_id, carts.updated, carts.created
            HAVING COUNT(carts_products.id) > 0
        ",
            new SqlLiteral("'{$days} days'")
        );

        $result = [];
        foreach ($data as $row) {
            $arr = [
                'id' => $row->offsetGet('id'),
                'customers_id' => $row->offsetGet('customers_id'),
                'updated' => $row->offsetGet('updated'),
                'created' => $row->offsetGet('created'),
            ];
            $result[] = $this->prepareEntity($arr);
        }
        return $result;
    }

    /**
     * @param CartEntity $entity
     * @return array<string, mixed>
     */
    protected function prepareToSave(IEntity $entity): array
    {
        return [
            'customers_id' => $entity->getCustomer()->getId(),
        ];
    }
}
