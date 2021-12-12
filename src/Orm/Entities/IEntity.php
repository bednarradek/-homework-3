<?php

declare(strict_types=1);

namespace App\Orm\Entities;

interface IEntity
{
    public function getId(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function save(): void;
}
