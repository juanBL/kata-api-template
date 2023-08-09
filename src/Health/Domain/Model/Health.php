<?php

declare(strict_types=1);

namespace App\Health\Domain\Model;

class Health
{
    private int $status;

    public function __construct(int $status)
    {
        $this->status = $status;
    }
}
