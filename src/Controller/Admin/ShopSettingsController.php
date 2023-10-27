<?php

namespace App\Controller\Admin;

use App\Entity\Holiday;
use App\Entity\Option;
use App\Form\HolidayType;
use App\Form\OffDaysType;
use App\Repository\HolidayRepository;
use App\Service\HolidayService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/salon', name: 'app_admin_shop_settings_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class ShopSettingsController extends AbstractController
{
    public function __construct( private EntityManagerInterface $entityManager )
    {
    }

    #[Route( '/parametres', name: 'index' )]
    public function index() : Response
    {
        // get offdays
        $offdays = $this->entityManager->getRepository( Option::class )->findOneBy( ['name' => 'offdays'] );

        return $this->render( 'admin/shop_settings/index.html.twig', [
            'offdays' => $offdays,
        ] );
    }

    #[Route( '/jours-feries', name: 'offdays' )]
    public function offdays( Request $request ) : Response
    {
        $repository = $this->entityManager->getRepository( Option::class );
        $option = $repository->findOneBy( ['name' => 'offdays'] );
        if ( $option === null ) {
            $option = new Option( 'Jours de fermeture', 'offdays', '', ChoiceType::class );
            $this->entityManager->persist( $option );
            $this->entityManager->flush();
        }

        $offDaysArray = explode( ',', $option->getValue() );
        $formOffDays = $this->createForm( OffDaysType::class, ['offdays' => $offDaysArray] );


        $formOffDays->handleRequest( $request );

        if ( $formOffDays->isSubmitted() && $formOffDays->isValid() ) {
            $offdays = $formOffDays->get( 'offdays' )->getData();
            $offdays = implode( ',', $offdays );
            $option->setValue( $offdays );

            $this->entityManager->persist( $option );
            $this->entityManager->flush();

            $this->addToast( 'success', 'Les jours de fermeture ont été mis à jour.' );

            return $this->redirectToRoute( 'app_admin_shop_settings_offdays' );
        }

        return $this->render( 'admin/shop_settings/off-days.html.twig', [
            'form' => $formOffDays->createView(),
        ] );
    }


    #[Route( '/vacances', name: 'holidays' )]
    public function holidays( HolidayService $holidayService ) : Response
    {

        $holidays = $holidayService->getAll();

        return $this->render( 'admin/shop_settings/holidays/holidays.html.twig', [
            'holidays' => $holidays,
        ] );
    }

    /**
     * @throws Exception
     */
    #[Route( '/vacances/ajout', name: 'holidays_new' )]
    public function addHolidays( Request $request, HolidayService $holidayService ) : Response
    {

        $holiday = new Holiday();
        $formHoliday = $this->createForm( HolidayType::class, $holiday );

        $formHoliday->handleRequest( $request );

        if ( $formHoliday->isSubmitted() && $formHoliday->isValid() ) {
            $holidayService->addHoliday( $holiday );
            $this->addToast( 'success', 'Les vacances ont été ajoutées.' );

            return $this->redirectToRoute( 'app_admin_shop_settings_holidays' );
        }

        return $this->render( 'admin/shop_settings/holidays/add-holiday.html.twig', [
            'form' => $formHoliday->createView(),
        ] );
    }

    #[Route( '/vacances/{id}/modifier', name: 'holidays_edit' )]
    public function editHolidays( Request $request, HolidayService $holidayService, Holiday $holiday ) : Response
    {

        $formHoliday = $this->createForm( HolidayType::class, $holiday );

        $formHoliday->handleRequest( $request );

        if ( $formHoliday->isSubmitted() && $formHoliday->isValid() ) {
            $holidayService->updateHoliday( $holiday );
            $this->addToast( 'success', 'Les vacances ont été modifiées.' );

            return $this->redirectToRoute( 'app_admin_shop_settings_holidays' );
        }

        return $this->render( 'admin/shop_settings/holidays/edit-holiday.html.twig', [
            'form' => $formHoliday->createView(),
        ] );
    }

    #[Route( '/{id}', name: 'holidays_delete', methods: ['POST'] )]
    public function delete( Request $request, Holiday $holiday, HolidayRepository $holidayRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $holiday->getId(), $request->request->get( '_token' ) ) ) {
            $holidayRepository->remove( $holiday, true );

            $this->addToast( 'success', 'Prestation supprimée avec succès' );
        }

        return $this->redirectToRoute( 'app_admin_shop_settings_holidays', [], Response::HTTP_SEE_OTHER );
    }

}