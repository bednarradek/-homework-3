<?php

declare(strict_types=1);

namespace App\Orm\Repositories;

use App\Orm\Entities\IEntity;

interface IRepository
{
    public function findById(string $id): ?IEntity;

    /**
     * @param array<string, mixed> $where
     * @return IEntity[]
     */
    public function find(array $where): array;

    /**
     * @return IEntity[]
     */
    public function findAll(): array;

    public function delete(IEntity $entity): void;
    public function save(IEntity $entity): void;
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollBack(): void;
}
