<?php

namespace App\Http\Api\Controller;

use App\Domain\Appointment\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/appointments', name: 'appointments_' )]
class ApiAppointmentController extends AbstractController
{
    public function __construct( private readonly AppointmentService $appointmentService )
    {
    }

    #[Route( '/', name: 'appointments', methods: ['GET'] )]
    public function getAppointments() : JsonResponse
    {
        $appointments = $this->appointmentService->getAppointments();
        $data = [];
        foreach ( $appointments as $appointment ) {
            $data[] = array(
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()->format( 'Y-m-d' ),
                'time' => $appointment->getTime()->format( 'H:i' ),
                'status' => $appointment->getStatus(),
                'client' => array(
                    'fullname' => $appointment->getClient()->getFullname(),
                    'email' => $appointment->getClient()->getEmail(),
                    'phone' => $appointment->getClient()->getPhone(),
                ),
                'presentation' => array(
                    'name' => $appointment->getPrestation()->getName(),
                    'description' => $appointment->getPrestation()->getDescription(),
                    'duration' => $appointment->getPrestation()->getDuration()->format( 'H:i' ),
                    'price' => $appointment->getPrestation()->getPrice(),
                )

            );
        }

        // return json response
        return $this->json( $data );
    }

    #[Route( '/reserved', name: 'reserved_appointments', methods: ['GET'] )]
    public function getReservedAppointments() : JsonResponse
    {
        $reservedAppointments = $this->appointmentService->getReservedAppointments();
        $dataJson = [];
        foreach ( $reservedAppointments as $reservedAppointment ) {
            $dataJson[] = [
                'date' => $reservedAppointment->getDate()->format( 'Y-m-d' ),
                'time' => $reservedAppointment->getTime()->format( 'H:i' ),
            ];
        }

        return $this->json( $dataJson );
    }
}