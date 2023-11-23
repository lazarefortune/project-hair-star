<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Dto\Admin\Booking\BookingDto;
use App\Entity\Booking;
use App\Form\AdminAddBookingType;
use App\Service\BookingService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/rendez-vous', name: 'booking_' )]
class AdminBookingController extends AbstractController
{
    public function __construct( private readonly BookingService $bookingService )
    {
    }

    #[Route( '/', name: 'index' )]
    public function index() : Response
    {
        return $this->render( 'admin/booking/index.html.twig', [
            'bookings' => $this->bookingService->getBookings(),
        ] );
    }

    #[Route( '/ajouter', name: 'add' )]
    public function add( Request $request ) : Response
    {
        $booking = new Booking();

        [$form, $response] = $this->createFormBooking( $request );

        if ( $response ) {
            $this->addToast( 'success', 'Votre rendez-vous a bien été enregistré.' );
            return $response;
        }

        return $this->render( 'admin/booking/add.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    private function createFormBooking( Request $request, Booking $booking = new Booking() ) : array
    {
        $form = $this->createForm( AdminAddBookingType::class, new BookingDto( $booking ) );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->bookingService->addBooking( $data );

            return [$form, $this->redirectToRoute( 'app_admin_booking_index' )];
        }

        return [$form, null];
    }

    public function createUpdateFormBooking( Request $request, Booking $booking = new Booking() ) : array
    {
        $form = $this->createForm( AdminAddBookingType::class, new BookingDto( $booking ) );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->bookingService->updateBookingWithDto( $data );

            return [$form, $this->redirectToRoute( 'app_admin_booking_index' )];
        }

        return [$form, null];
    }

    #[Route( '/{id}', name: 'show' )]
    public function show( Booking $booking = null ) : Response
    {
        if ( !$booking ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_booking_index' );
        }

        return $this->render( 'admin/booking/show.html.twig', [
            'booking' => $booking,
        ] );
    }

    #[Route( '/{id}/modifier', name: 'edit' )]
    public function edit( Request $request, Booking $booking = null ) : Response
    {
        if ( !$booking ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_booking_index' );
        }

        [$form, $response] = $this->createUpdateFormBooking( $request, $booking );

        if ( $response ) {
            $this->addToast( 'success', 'Votre rendez-vous a bien été modifié.' );
            return $response;
        }

        return $this->render( 'admin/booking/update.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    #[Route( '/{id}/confirmer', name: 'confirm' )]
    public function confirmBooking( Request $request, Booking $booking = null ) : Response
    {
        if ( !$booking ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_booking_index' );
        }

        if ( $this->isCsrfTokenValid( 'confirm_booking' . $booking->getId(), $request->request->get( '_token' ) ) ) {
            $this->bookingService->confirmBooking( $booking );

            $this->addToast( 'success', 'Rendez-vous confirmé avec succès.' );
            return $this->redirectToRoute( 'app_admin_booking_show', ['id' => $booking->getId()] );
        }

        return $this->redirectToRoute( 'app_admin_booking_index' );
    }

    #[Route( '/{id}/annuler', name: 'cancel' )]
    public function cancelBooking( Request $request, Booking $booking = null ) : Response
    {
        if ( !$booking ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_booking_index' );
        }

        if ( $this->isCsrfTokenValid( 'cancel_booking' . $booking->getId(), $request->request->get( '_token' ) ) ) {
            $this->bookingService->cancelBooking( $booking );

            $this->addToast( 'success', 'Rendez-vous annulé avec succès.' );
            return $this->redirectToRoute( 'app_admin_booking_show', ['id' => $booking->getId()] );
        }

        return $this->redirectToRoute( 'app_admin_booking_index' );
    }

    #[Route( '/{id}/supprimer', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, Booking $booking ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $booking->getId(), $request->request->get( '_token' ) ) ) {
            $this->bookingService->deleteBooking( $booking );

            $this->addToast( 'success', 'Rendez-vous supprimé avec succès.' );
        }

        return $this->redirectToRoute( 'app_admin_booking_index', [], Response::HTTP_SEE_OTHER );
    }
}
