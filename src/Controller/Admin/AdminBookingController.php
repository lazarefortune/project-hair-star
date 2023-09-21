<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\AdminAddBookingType;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/rendez-vous', name: 'app_admin_booking_' )]
class AdminBookingController extends AbstractController
{
    public function __construct( private BookingService $bookingService )
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
        $form = $this->createForm( AdminAddBookingType::class, $booking );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->bookingService->addBooking( $booking );
            $this->addFlash( 'success', 'Le rendez-vous a bien été ajouté.' );
            return $this->redirectToRoute( 'app_admin_booking_index' );
        }

        return $this->render( 'admin/booking/add.html.twig', [
            'form' => $form->createView(),
        ] );
    }

//    #[Route( '/{id}/modifier', name: 'edit' )]
//    public function edit( Request $request, Booking $booking ) : Response
//    {
//        $form = $this->createForm( AdminAddBookingType::class, $booking );
//        $form->handleRequest( $request );
//
//        if ( $form->isSubmitted() && $form->isValid() ) {
//            $this->bookingService->editBooking( $booking );
//            $this->addFlash( 'success', 'Le rendez-vous a bien été modifié.' );
//            return $this->redirectToRoute( 'app_admin_booking_index' );
//        }
//
//        return $this->render( 'admin/booking/edit.html.twig', [
//            'form' => $form->createView(),
//        ] );
//    }

    #[Route( '/{id}', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, Booking $booking ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $booking->getId(), $request->request->get( '_token' ) ) ) {
            $this->bookingService->deleteBooking( $booking );

            $this->addFlash( 'success', 'Rendez-vous annulé avec succès.' );
        }

        return $this->redirectToRoute( 'app_admin_booking_index', [], Response::HTTP_SEE_OTHER );
    }
}
