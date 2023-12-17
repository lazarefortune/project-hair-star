<?php

namespace App\Infrastructure\Mailing\Enum;

enum AppointmentEmailEnum
{
    public const APPOINTMENT_NEW = [
        'description' => 'Mail de nouvelle réservation',
        'value' => 'appointment_new'
    ];

    public const APPOINTMENT_CONFIRMATION = [
        'description' => 'Mail de confirmation de rendez-vous',
        'value' => 'appointment_confirmation'
    ];

    public const APPOINTMENT_REMINDER = [
        'description' => 'Mail de rappel de rendez-vous',
        'value' => 'appointment_reminder'
    ];

    public const APPOINTMENT_UPDATED = [
        'description' => 'Mail de modification de rendez-vous',
        'value' => 'appointment_updated'
    ];
    public const APPOINTMENT_CANCELLED = [
        'description' => 'Mail d\'annulation de rendez-vous',
        'value' => 'appointment_cancelled'
    ];

    public static function getValues() : array
    {
        return [
            self::APPOINTMENT_REMINDER,
            self::APPOINTMENT_CONFIRMATION,
            self::APPOINTMENT_UPDATED,
            self::APPOINTMENT_CANCELLED,
        ];
    }
}
