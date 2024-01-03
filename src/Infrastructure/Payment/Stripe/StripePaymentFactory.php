<?php

namespace App\Infrastructure\Payment\Stripe;

use App\Domain\Payment\Service\StripePayment;
use Stripe\PaymentIntent;

class StripePaymentFactory
{
    public function __construct( private readonly StripeApi $stripeApi )
    {
    }

    public function createPaymentFromIntent( PaymentIntent $intent ) : StripePayment
    {

    }
}