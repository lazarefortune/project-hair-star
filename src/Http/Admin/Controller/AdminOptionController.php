<?php

namespace App\Http\Admin\Controller;

use App\Domain\Application\Entity\Option;
use App\Domain\Application\Form\EditOptionForm;
use App\Domain\Application\Service\OptionService;
use App\Http\Controller\AbstractController;
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
        $formEditOption = $this->createForm( EditOptionForm::class, $option );

//        $formOption = $this->createForm( OptionType::class, $option );

        return $this->render( 'admin/option/edit.html.twig', [
            'formOption' => $formEditOption->createView(),
        ] );
    }
}
