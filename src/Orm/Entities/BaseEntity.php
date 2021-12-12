<?php

declare(strict_types=1);

namespace App\Orm\Entities;

use App\Orm\Repositories\IRepository;
use JsonSerializable;
use Nette\Utils\DateTime;

abstract class BaseEntity implements IEntity, JsonSerializable
{
    protected ?string $id = null;
    private ?DateTime $updated = null;
    private ?DateTime $created = null;
    private IRepository $repository;

    public function __construct(IRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'updated' => $this->getUpdated()?->format('d.m.Y H:i:s'),
            'created' => $this->getCreated()?->format('d.m.Y H:i:s'),
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }

    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    public function setCreated(?DateTime $created): void
    {
        $this->created = $created;
    }

    public function save(): void
    {
        $this->repository->save($this);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
