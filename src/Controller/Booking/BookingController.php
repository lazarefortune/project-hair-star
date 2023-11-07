<?php

namespace App\Controller\Booking;

use App\Controller\AbstractController;
use App\Dto\Admin\Booking\BookingDto;
use App\Dto\Admin\Booking\BookingManageUpdateDto;
use App\Form\Booking\BookingManageUpdateType;
use App\Service\BookingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/rendez-vous', name: 'app_booking_' )]
class BookingController extends AbstractController
{
    public function __construct(
        private readonly BookingService $bookingService,
    )
    {
    }

    #[Route( '/gestion/{token}', name: 'manage_view' )]
    public function manageView(
        string $token,
    ) : Response
    {

        $booking = $this->bookingService->getBookingByToken( $token );

        if ( !$booking ) {
            throw $this->createNotFoundException( 'La réservation n\'existe pas' );
        }


        return $this->render( 'booking/manage-view.html.twig', [
            'booking' => $booking,
        ] );
    }

    #[Route( '/gestion/{token}/modifier', name: 'manage_update', methods: ['GET', 'POST'] )]
    public function manageUpdate(
        string  $token,
        Request $request
    ) : Response
    {
        $booking = $this->bookingService->getBookingByToken( $token );

        if ( !$booking ) {
            throw $this->createNotFoundException( 'La réservation n\'existe pas' );
        }

        $form = $this->createForm( BookingManageUpdateType::class, new BookingManageUpdateDto( $booking ) );

        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $bookingDto = new BookingDto( $booking );
            $bookingDto->bookingDate = $form->getData()->bookingDate;
            $bookingDto->bookingTime = $form->getData()->bookingTime;
            $this->bookingService->updateBookingWithDto( $bookingDto );

            $this->addToast( 'success', 'La réservation a bien été modifiée' );

            return $this->redirectToRoute( 'app_booking_manage_view', ['token' => $token] );
        }


        return $this->render( 'booking/manage-update.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking,
        ] );

    }
}