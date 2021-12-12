<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\BaseEntity;
use App\Orm\Entities\IEntity;
use Exception;
use Nette\Database\Explorer;
use Nette\Utils\DateTime;

abstract class BaseRepository implements IRepository
{
    protected string $tableName = '';
    protected Explorer $explorer;

    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param array<string, mixed> $data
     * @return IEntity
     */
    abstract protected function prepareEntity(array $data): IEntity;

    /**
     * @param BaseEntity $entity
     * @param array<string, mixed> $data
     * @return void
     * @throws Exception
     */
    protected function prepareDateColumns(BaseEntity $entity, array $data): void
    {
        if ($data['updated']) {
            $entity->setUpdated($data['updated']);
        }

        if ($data['created']) {
            $entity->setCreated($data['created']);
        }
    }

    public function findById(string $id): ?IEntity
    {
        $data = $this->explorer->table($this->tableName)->get($id)?->toArray();
        if ($data) {
            return $this->prepareEntity($data);
        } else {
            return null;
        }
    }

    /**
     * @param array<string, mixed> $where
     * @return IEntity[]
     */
    public function find(array $where): array
    {
        $data = $this->explorer->table($this->tableName)->where($where)->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = $this->prepareEntity($row->toArray());
        }
        return $result;
    }

    /**
     * @param array<string, mixed> $where
     * @return IEntity|null
     */
    public function findFirst(array $where): ?IEntity
    {
        $data = $this->explorer->table($this->tableName)->where($where)->fetch();
        if ($data) {
            return $this->prepareEntity($data->toArray());
        }
        return null;
    }

    /**
     * @return IEntity[]
     */
    public function findAll(): array
    {
        return $this->find([]);
    }

    public function delete(IEntity $entity): void
    {
        $this->explorer->table($this->tableName)->get($entity->getId())->delete();
    }

    /**
     * @param IEntity $entity
     * @return IEntity[]
     */
    abstract protected function prepareToSave(IEntity $entity): array;

    public function save(IEntity $entity): void
    {
        $data = $this->prepareToSave($entity);

        if ($entity->getId() !== null) {
            $this->explorer->table($this->tableName)->get($entity->getId())->update($data);
        } else {
            $this->explorer->table($this->tableName)->insert($data);
        }
    }

    public function beginTransaction(): void
    {
        $this->explorer->beginTransaction();
    }

    public function commit(): void
    {
        $this->explorer->commit();
    }

    public function rollBack(): void
    {
        $this->explorer->rollBack();
    }
}
