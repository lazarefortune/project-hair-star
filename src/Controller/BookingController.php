<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Dto\Admin\Booking\BookingDto;
use App\Dto\Admin\Booking\BookingManageUpdateDto;
use App\Entity\Booking;
use App\Form\Booking\BookingManageUpdateType;
use App\Service\BookingService;
use App\Service\Payment\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/rendez-vous', name: 'app_booking_' )]
class BookingController extends AbstractController
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly StripePayment  $stripePaymentService,
    )
    {
    }

    #[Route( '/gestion/{token}', name: 'manage' )]
    public function viewManagement( string $token ) : Response
    {
        $booking = $this->getBookingOrThrowNotFound( $token );

        return $this->render( 'booking/manage-view.html.twig', [
            'booking' => $booking,
        ] );
    }

    #[Route( '/gestion/{token}/modification', name: 'manage_edit', methods: ['GET', 'POST'] )]
    public function editManagement( string $token, Request $request ) : Response
    {
        $booking = $this->getBookingOrThrowNotFound( $token );
        $bookingUpdateDto = new BookingManageUpdateDto( $booking );
        $form = $this->createForm( BookingManageUpdateType::class, $bookingUpdateDto );
        $form->handleRequest( $request );

        if ( $this->handleBookingUpdateForm( $form, $booking ) ) {
            $this->addToast( 'success', 'La réservation a bien été modifiée' );
            return $this->redirectToRoute( 'app_booking_manage', ['token' => $token] );
        }

        return $this->render( 'booking/manage-update.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
        ] );
    }

    private function getBookingOrThrowNotFound( string $token ) : Booking
    {
        $booking = $this->bookingService->getBookingByToken( $token );
        if ( !$booking ) {
            throw $this->createNotFoundException( 'La réservation n\'existe pas' );
        }
        return $booking;
    }

    private function handleBookingUpdateForm( $form, Booking $booking ) : bool
    {
        if ( $form->isSubmitted() && $form->isValid() ) {
            $bookingDto = new BookingDto( $booking );
            $bookingDto->bookingDate = $form->getData()->bookingDate;
            $bookingDto->bookingTime = $form->getData()->bookingTime;
            $this->bookingService->updateBookingWithDto( $bookingDto );

            return true;
        }
        return false;
    }

    #[Route( '/paiement/demarrer/{id}', name: 'payment_start' )]
    #[ParamConverter( 'booking', options: ['mapping' => ['id' => 'id']] )]
    public function startPayment( Request $request, Booking $booking ) : Response
    {
        $url = $this->stripePaymentService->payBooking( $booking );
        return $this->redirect( $url );
    }

    #[Route( '/paiement/resultat/{id}', name: 'payment_result' )]
    public function paymentResult( Request $request, Booking $booking ) : Response
    {
        $status = $request->query->get( 'success' ) === '1' ? 'success' : 'failure';
        return $this->render( "booking/payment/payment_result.html.twig", [
            'status' => $status,
            'booking' => $booking,
        ] );
    }
}
