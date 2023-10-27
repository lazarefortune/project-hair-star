<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/admin/parametres', name: 'app_admin_settings_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class SettingsController extends AbstractController
{
    #[Route( '/', name: 'index', methods: ['GET'] )]
    public function index() : Response
    {
        return $this->render( 'admin/settings/index.html.twig' );
    }
}