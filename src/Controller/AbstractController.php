<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function getUserOrThrow() : UserInterface
    {
        $user = $this->getUser();

        if ( !$user ) {
            throw $this->createAccessDeniedException( 'Vous devez être connecté pour accéder à cette page' );
        }

        return $user;
    }

    /**
     * Redirige l'utilisateur vers la page précédente ou la route en cas de fallback.
     */
    protected function redirectBack( string $route, array $params = [] ) : RedirectResponse
    {
        /** @var RequestStack $stack */
        $stack = $this->container->get( 'request_stack' );
        $request = $stack->getCurrentRequest();
        if ( $request && $request->server->get( 'HTTP_REFERER' ) ) {
            return $this->redirect( $request->server->get( 'HTTP_REFERER' ) );
        }

        return $this->redirectToRoute( $route, $params );
    }

    /**
     * Affiche la liste de erreurs sous forme de message flash.
     */
    protected function flashErrors( FormInterface $form ) : void
    {
        /** @var FormError[] $errors */
        $errors = $form->getErrors();
        $messages = [];
        foreach ( $errors as $error ) {
            $messages[] = $error->getMessage();
        }
        $this->addFlash( 'error', implode( "\n", $messages ) );
    }
}