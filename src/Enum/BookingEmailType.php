<?php

namespace App\Enum;

enum BookingEmailType
{
    public const BOOKING_NEW = [
        'description' => 'Mail de nouvelle réservation',
        'value' => 'booking_new'
    ];

    public const BOOKING_CONFIRMATION = [
        'description' => 'Mail de confirmation de rendez-vous',
        'value' => 'booking_confirmation'
    ];

    public const BOOKING_REMINDER = [
        'description' => 'Mail de rappel de rendez-vous',
        'value' => 'booking_reminder'
    ];

    public const BOOKING_UPDATED = [
        'description' => 'Mail de modification de rendez-vous',
        'value' => 'booking_updated'
    ];
    public const BOOKING_CANCELLED = [
        'description' => 'Mail d\'annulation de rendez-vous',
        'value' => 'booking_cancelled'
    ];

    public static function getValues() : array
    {
        return [
            self::BOOKING_REMINDER,
            self::BOOKING_CONFIRMATION,
            self::BOOKING_UPDATED,
            self::BOOKING_CANCELLED,
        ];
    }
}
