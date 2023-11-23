<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\EditOptionType;
use App\Service\OptionService;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/option', name: 'option_' )]
#[IsGranted( 'ROLE_ADMIN' )]
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
        $formEditOption = $this->createForm( EditOptionType::class, $option );

//        $formOption = $this->createForm( OptionType::class, $option );

        return $this->render( 'admin/option/edit.html.twig', [
            'formOption' => $formEditOption->createView(),
        ] );
    }
}
