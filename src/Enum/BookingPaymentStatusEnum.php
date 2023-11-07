<?php

namespace App\Enum;

enum BookingPaymentStatusEnum: string
{
    const __default = self::PENDING;
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case FAILED = 'failed';
    

    public static function getValues() : array
    {
        return [
            self::SUCCESS,
            self::PENDING,
            self::FAILED,
        ];
    }
}