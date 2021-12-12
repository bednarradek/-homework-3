<?php

declare(strict_types=1);

namespace App\Models;

use JsonSerializable;

class StatusMessage implements JsonSerializable
{
    public function __construct(private string $message, private int $status = 200)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message
        ];
    }
}
