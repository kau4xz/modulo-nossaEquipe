<?php

declare(strict_types=1);

namespace Src\App\Http\Exceptions;

use Src\App\Enums\HttpStatus;

abstract class DomainException extends \Exception
{
    protected int $statusCode = HttpStatus::BAD_REQUEST->value;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
