<?php

namespace App\Http\Controller;

use App\Domain\Appointment\Dto\AppointmentData;
use App\Domain\Appointment\Dto\AppointmentManageUpdateData;
use App\Domain\Appointment\Entity\Appointment;
use App\Domain\Appointment\Form\AppointmentManageUpdateForm;
use App\Domain\Appointment\Service\AppointmentService;
use App\Domain\Payment\PaymentService;
use App\Domain\Payment\Service\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/rendez-vous', name: 'appointment_' )]
class AppointmentController extends AbstractController
{
    public function __construct(
        private readonly PaymentService     $paymentService,
        private readonly AppointmentService $appointmentService,
    )
    {
    }

    #[Route( '/gestion/{token}', name: 'manage' )]
    public function viewManagement( string $token ) : Response
    {
        $appointment = $this->getAppointmentOrNull( $token );

        return $this->render( 'appointment/manage-view.html.twig', [
            'appointment' => $appointment,
        ] );
    }

    #[Route( '/gestion/{token}/modification', name: 'manage_edit', methods: ['GET', 'POST'] )]
    public function editManagement( string $token, Request $request ) : Response
    {
        $appointment = $this->getAppointmentOrNull( $token );
        $appointmentUpdateDto = new AppointmentManageUpdateData( $appointment );
        $form = $this->createForm( AppointmentManageUpdateForm::class, $appointmentUpdateDto );
        $form->handleRequest( $request );

        if ( $this->handleAppointmentUpdateForm( $form, $appointment ) ) {
            $this->addFlash( 'success', 'La réservation a bien été modifiée' );
            return $this->redirectToRoute( 'app_appointment_manage', ['token' => $token] );
        }

        return $this->render( 'appointment/manage-update.html.twig', [
            'form' => $form->createView(),
            'appointment' => $appointment,
        ] );
    }

    private function getAppointmentOrNull( string $token ) : ?Appointment
    {
        $appointment = $this->appointmentService->getAppointmentByToken( $token );
        if ( !$appointment ) {
            return null;
        }
        return $appointment;
    }

    private function handleAppointmentUpdateForm( $form, Appointment $appointment ) : bool
    {
        if ( $form->isSubmitted() && $form->isValid() ) {
            $appointmentDto = new AppointmentData( $appointment );
            $appointmentDto->date = $form->getData()->date;
            $appointmentDto->time = $form->getData()->time;
            $this->appointmentService->updateAppointmentWithDto( $appointmentDto );

            return true;
        }
        return false;
    }

    #[Route( '/paiement/demarrer/{id<\d+>}', name: 'payment_start' )]
    #[ParamConverter( 'appointment', options: ['mapping' => ['id' => 'id']] )]
    public function startPayment( Appointment $appointment ) : Response
    {
        $paymentMethod = 'stripe';
        try {
            $url = $this->paymentService->pay( $appointment->getAmount(), $appointment, $paymentMethod );
            return $this->redirect( $url );
        } catch ( \Exception $e ) {
            $this->addFlash( 'danger', $e->getMessage() );
            return $this->redirectToRoute( 'app_appointment_manage', ['token' => $appointment->getToken()] );
        }
    }

    #[Route( '/paiement/acompte/demarrer/{id<\d+>}', name: 'payment_acompte_start' )]
    #[ParamConverter( 'appointment', options: ['mapping' => ['id' => 'id']] )]
    public function startAcomptePayment( Appointment $appointment ) : Response
    {
        $this->addFlash( 'danger', 'Le paiement n\'est pas encore disponible' );
        return $this->redirectToRoute( 'app_appointment_manage', ['token' => $appointment->getToken()] );
    }

    //app_appointment_save_for_later
    #[Route( '/paiement/sauvegarder-pour-plus-tard/{id<\d+>}', name: 'save_for_later' )]
    #[ParamConverter( 'appointment', options: ['mapping' => ['id' => 'id']] )]
    public function saveForLater( Appointment $appointment ) : Response
    {
        $this->addFlash( 'danger', 'La sauvegarde pour plus tard n\'est pas encore disponible' );
        return $this->redirectToRoute( 'app_appointment_manage', ['token' => $appointment->getToken()] );
    }


    #[Route( '/paiement/resultat/{id<\d+>}', name: 'payment_result' )]
    public function paymentResult( Request $request, Appointment $appointment ) : Response
    {
        $paymentSuccess = $request->query->get( 'success' ) === '1';
        $status = $request->query->get( 'success' ) === '1' ? 'success' : 'failure';

        ( $paymentSuccess ) ? $this->addFlash( 'success', 'Le paiement a bien été effectué' ) : $this->addFlash( 'danger', 'Le paiement a échoué' );
        return $this->redirectToRoute( 'app_appointment_manage', ['token' => $appointment->getToken()] );
    }
}
