<?php

namespace App\Enums;

enum ErrorCodeEnum: string
{
    case Code400 = "Validation errors";
    case Code404 = "404 not found";
    case Code403 = "403 forbidden";
    case Code500 = "Internal server error";

    public static function getMessage(int $code): string
    {
        return match ($code) {
            400 => self::Code400->value,
            404 => self::Code404->value,
            403 => self::Code403->value,
            500 => self::Code500->value,
            default => 'Unexpected error',
        };
    }
}
