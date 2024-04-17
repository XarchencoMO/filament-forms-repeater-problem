<?php

namespace App\Enums;

enum CustomerStatusEnum: int
{
    case New = 1;
    case Old = 2;

    public static function toArray()
    {
        return [
            CustomerStatusEnum::New->value => CustomerStatusEnum::New->name,
            CustomerStatusEnum::Old->value => CustomerStatusEnum::Old->name,
        ];
    }
}
