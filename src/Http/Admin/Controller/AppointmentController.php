<?php

namespace App\Http\Admin\Controller;

use App\Domain\Appointment\Dto\AppointmentData;
use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Appointment\Form\NewAppointmentForm;
use App\Domain\Appointment\Service\AppointmentService;
use App\Http\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/rendez-vous', name: 'appointment_' )]
class AppointmentController extends AbstractController
{
    public function __construct( private readonly AppointmentService $appointmentService )
    {
    }

    #[Route( '/', name: 'index' )]
    public function index() : Response
    {
        $appointments = $this->appointmentService->getAppointments();
        $appointmentsToday = [];
        foreach ( $appointments as $appointment ) {
            if ( $appointment->getDate()->format( 'Y-m-d' ) === date( 'Y-m-d' ) ) {
                $appointmentsToday[] = $appointment;
            }
        }

        // group appointments by date and order by date
        $appointmentsByDate = [];
        foreach ( $appointments as $appointment ) {
            $date = $appointment->getDate()->format( 'Y-m-d' );
            if ( !isset( $appointmentsByDate[$date] ) ) {
                $appointmentsByDate[$date] = [];
            }
            $appointmentsByDate[$date][] = $appointment;
        }
        ksort( $appointmentsByDate );

        // Separate after today and before today
        $appointmentsAfterToday = [];
        $appointmentsBeforeToday = [];
        foreach ( $appointmentsByDate as $date => $appointments ) {
            if ( $date > date( 'Y-m-d' ) ) {
                $appointmentsAfterToday[$date] = $appointments;
            } else {
                $appointmentsBeforeToday[$date] = $appointments;
            }
        }
        // Sort after today by date desc and before today by date asc
        krsort( $appointmentsBeforeToday );
        ksort( $appointmentsAfterToday );

        // remove today in past appointments
        if ( isset( $appointmentsBeforeToday[date( 'Y-m-d' )] ) ) {
            unset( $appointmentsBeforeToday[date( 'Y-m-d' )] );
        }

        return $this->render( 'admin/appointment/index.html.twig', [
            'appointments' => $this->appointmentService->getAppointments(),
            'appointmentsToday' => $appointmentsToday,
            'appointmentsAfterToday' => $appointmentsAfterToday,
            'appointmentsBeforeToday' => $appointmentsBeforeToday,
        ] );
    }

    #[Route( '/new', name: 'new', methods: ['GET', 'POST'] )]
    public function new( Request $request ) : Response
    {
        [$form, $response] = $this->createFormAppointment( $request );

        if ( $response ) {
            $this->addToast( 'success', 'Votre rendez-vous a bien été enregistré.' );
            return $response;
        }

        return $this->render( 'admin/appointment/new.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    private function createFormAppointment( Request $request, Appointment $appointment = new Appointment() ) : array
    {
        $form = $this->createForm( NewAppointmentForm::class, new AppointmentData( $appointment ) );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->appointmentService->addAppointment( $data );

            return [$form, $this->redirectToRoute( 'app_admin_appointment_index' )];
        }

        return [$form, null];
    }

    public function createUpdateFormAppointment( Request $request, Appointment $appointment = new Appointment() ) : array
    {
        $form = $this->createForm( NewAppointmentForm::class, new AppointmentData( $appointment ) );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData();
            $this->appointmentService->updateAppointmentWithDto( $data );

            return [$form, $this->redirectToRoute( 'app_admin_appointment_index' )];
        }

        return [$form, null];
    }

    #[Route( '/{id<\d+>}', name: 'show', methods: ['GET'] )]
    public function show( Appointment $appointment = null ) : Response
    {
        if ( !$appointment ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_appointment_index' );
        }

        return $this->render( 'admin/appointment/show.html.twig', [
            'appointment' => $appointment,
        ] );
    }

    #[Route( '/{id<\d+>}/edit', name: 'edit', methods: ['GET', 'POST'] )]
    public function edit( Request $request, Appointment $appointment = null ) : Response
    {
        if ( !$appointment ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_appointment_index' );
        }

        [$form, $response] = $this->createUpdateFormappointment( $request, $appointment );

        if ( $response ) {
            $this->addToast( 'success', 'Votre rendez-vous a bien été modifié.' );
            return $response;
        }

        return $this->render( 'admin/appointment/update.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    #[Route( '/{id<\d+>}/confirm', name: 'confirm', methods: ['GET', 'POST'] )]
    public function confirm( Request $request, Appointment $appointment = null ) : Response
    {
        if ( !$appointment ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_appointment_index' );
        }

        if ( $this->isCsrfTokenValid( 'confirm_appointment' . $appointment->getId(), $request->request->get( '_token' ) ) ) {
            $this->appointmentService->confirmAppointment( $appointment );

            $this->addToast( 'success', 'Rendez-vous confirmé avec succès.' );
            return $this->redirectToRoute( 'app_admin_appointment_show', ['id' => $appointment->getId()] );
        }

        return $this->redirectToRoute( 'app_admin_appointment_index' );
    }

    #[Route( '/{id<\d+>}/cancel', name: 'cancel' )]
    public function cancel( Request $request, Appointment $appointment = null ) : Response
    {
        if ( !$appointment ) {
            $this->addToast( 'danger', 'Rendez-vous introuvable.' );
            return $this->redirectToRoute( 'app_admin_appointment_index' );
        }

        if ( $this->isCsrfTokenValid( 'cancel_appointment' . $appointment->getId(), $request->request->get( '_token' ) ) ) {
            $this->appointmentService->cancelAppointment( $appointment );

            $this->addToast( 'success', 'Rendez-vous annulé avec succès.' );
            return $this->redirectToRoute( 'app_admin_appointment_show', ['id' => $appointment->getId()] );
        }

        return $this->redirectToRoute( 'app_admin_appointment_index' );
    }

    #[Route( '/{id<\d+>}/delete', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, Appointment $appointment ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $appointment->getId(), $request->request->get( '_token' ) ) ) {
            $this->appointmentService->deleteAppointment( $appointment );

            $this->addToast( 'success', 'Rendez-vous supprimé avec succès.' );
        }

        return $this->redirectToRoute( 'app_admin_appointment_index', [], Response::HTTP_SEE_OTHER );
    }
}
