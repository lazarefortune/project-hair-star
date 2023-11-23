<?php

namespace App\Enum;

enum UserEmailType
{
    public const ACCOUNT_WELCOME = [
        'description' => 'Mail de bienvenue',
        'value' => 'account_welcome'
    ];
    public const ACCOUNT_CONFIRMATION_SUCCESS = [
        'description' => 'Mail de confirmation de compte réussie',
        'value' => 'account_confirmation_success'
    ];


    public const ACCOUNT_REQUEST_CONFIRMATION = [
        'description' => 'Mail de demande de confirmation de compte',
        'value' => 'account_request_confirmation'
    ];
    public const PASSWORD_RESET = [
        'description' => 'Mail de réinitialisation de mot de passe',
        'value' => 'password_reset'
    ];

    public const ACCOUNT_REMOVE = [
        'description' => 'Mail de suppression de compte',
        'value' => 'account_remove'
    ];

    public const ACCOUNT_UPDATED_EMAIL_SUCCESS = [
        'description' => 'Mail de confirmation de changement d\'adresse email',
        'value' => 'account_updated_email_success'
    ];

    public const ACCOUNT_UPDATED_EMAIL_REQUEST_CONFIRMATION = [
        'description' => 'Mail de demande de confirmation de changement d\'adresse email',
        'value' => 'account_updated_email_request_confirmation'
    ];

    public const ACCOUNT_WELCOME_REQUEST_EMAIL_CONFIRMATION = [
        'description' => 'Mail de demande de confirmation d\'adresse email',
        'value' => 'account_welcome_request_email_confirmation'
    ];

    public const ACCOUNT_WELCOME_REQUEST_CONFIRMATION = [
        'description' => 'Mail de demande de confirmation de compte',
        'value' => 'account_welcome_request_confirmation'
    ];


    public static function getValues() : array
    {
        return [
            self::ACCOUNT_WELCOME,
            self::ACCOUNT_CONFIRMATION_SUCCESS,
            self::ACCOUNT_REQUEST_CONFIRMATION,
            self::PASSWORD_RESET,
            self::ACCOUNT_REMOVE,
            self::ACCOUNT_UPDATED_EMAIL_SUCCESS,
            self::ACCOUNT_UPDATED_EMAIL_REQUEST_CONFIRMATION,
            self::ACCOUNT_WELCOME_REQUEST_EMAIL_CONFIRMATION,
            self::ACCOUNT_WELCOME_REQUEST_CONFIRMATION,
        ];
    }
}