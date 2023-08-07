<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Service\OptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route( '/admin/option', name: 'app_admin_option_' )]
class AdminOptionController extends AbstractController
{

    public function __construct(
        private OptionService $optionService
    )
    {
    }

    #[Route( '/', name: 'index' )]
    public function index() : Response
    {
        return $this->render( 'admin/option/index.html.twig', [
            'options' => $this->optionService->getAll(),
        ] );
    }

    #[Route( '/{id}', name: 'edit' )]
    public function edit( Option $option ) : Response
    {
        $formOption = $this->createForm( OptionType::class, $option );

        return $this->render( 'admin/option/edit.html.twig', [
            'formOption' => $formOption->createView(),
        ] );
    }
}