<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbService
{
    private RequestStack $requestStack;
    private RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function generateBreadcrumb() : array
    {
        $breadcrumbs = [];
        $currentRequest = $this->requestStack->getCurrentRequest();
        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        $addBreadcrumb = function($label, $routeName, $parameters = []) use (&$breadcrumbs) {
            $breadcrumbs[] = [
                'label' => $label,
                'url' => $this->router->generate($routeName, $parameters),
            ];
        };

        switch ($route) {
            case 'app_admin_home':
                $addBreadcrumb('Accueil', 'app_admin_home');
                break;
            case 'app_admin_realisation_index':
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Réalisations', 'app_admin_realisation_index');
                break;
            case 'app_admin_account_index':
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Mon compte', 'app_admin_account_index');
                break;
            case 'app_admin_account_password':
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Mon compte', 'app_admin_account_index');
                $addBreadcrumb('Mot de passe', 'app_admin_account_password');
                break;
            case 'app_admin_realisation_new':
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Réalisations', 'app_admin_realisation_index');
                $addBreadcrumb('Nouvelle réalisation', 'app_admin_realisation_new');
                break;
            case 'app_admin_realisation_show':
                $itemId = $currentRequest->attributes->get('id');
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Réalisations', 'app_admin_realisation_index');
                $addBreadcrumb("$itemId", 'app_admin_realisation_show', ['id' => $itemId]);
                break;
            case 'app_admin_realisation_edit':
                $itemId = $currentRequest->attributes->get('id');
                $addBreadcrumb('Accueil', 'app_admin_home');
                $addBreadcrumb('Réalisations', 'app_admin_realisation_index');
                $addBreadcrumb("$itemId", 'app_admin_realisation_show', ['id' => $itemId]);
                $addBreadcrumb('Modification', 'app_admin_realisation_edit', ['id' => $itemId]);
                break;
        }
        return $breadcrumbs;
    }
}

