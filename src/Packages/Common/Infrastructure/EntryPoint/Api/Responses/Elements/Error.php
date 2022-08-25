<?php

declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\EntryPoint\Api\Responses\Elements;

class Error
{
    private int $status;
    private string $detail;

    public function __construct(int $status, string $detail) {
        $this->status = $status;
        $this->detail = $detail;
    }

    public function toArray(): array {
        return [
            "status" => $this->status,
            "detail" => $this->detail,
        ];
    }
}