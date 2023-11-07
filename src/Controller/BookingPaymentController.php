<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Service\Payment\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/paiement/rendez-vous' )]
class BookingPaymentController extends AbstractController
{
    public function __construct(
        readonly private StripePayment $stripePayment
    )
    {
    }

    #[Route( '/start/{id}', name: 'booking_payment_start' )]
    #[ParamConverter( 'booking', options: ['mapping' => ['id' => 'id']] )]
    public function startPayment( Request $request, Booking $booking ) : Response
    {
        $url = $this->stripePayment->startPayment( $booking );

        return $this->redirect( $url );
    }

    #[Route( '/success', name: 'booking_payment_success' )]
    public function success() : Response
    {
        return $this->render( 'booking/payment/success.html.twig' );
    }

    #[Route( '/annule', name: 'booking_payment_cancel' )]
    public function cancel() : Response
    {
        return $this->render( 'booking/payment/cancel.html.twig' );
    }
}