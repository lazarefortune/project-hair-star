<?php

namespace App\Http\Controller;

use App\Domain\Auth\Entity\User;
use App\Infrastructure\Flash\FlashMessage;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    protected function getUserOrThrow() : User
    {
        /** @var User $user */
        $user = $this->getUser();

        if ( !$user ) {
            throw $this->createAccessDeniedException( 'Vous devez être connecté pour accéder à cette page' );
        }

        return $user;
    }

    /**
     * Redirige l'utilisateur vers la page précédente ou la route en cas de fallback.
     * @param string $route
     * @param array<string, mixed> $params
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

    protected function addToastMessage( FlashMessage $flashMessage ) : void
    {
        $this->addFlash( 'toast_message', json_encode( [
            'type' => $flashMessage->getType(),
            'title' => $flashMessage->getTitle(),
            'message' => $flashMessage->getMessage(),
            'duration' => $flashMessage->getDuration(),
            'position' => $flashMessage->getPosition(),
        ] ) );
    }

    protected function addFlashMessage( FlashMessage $message ) : void
    {
        $this->addFlash( 'flash_message', json_encode( [
            'type' => $message->getType(),
            'title' => $message->getTitle(),
            'message' => $message->getMessage(),
            'duration' => $message->getDuration(),
            'position' => $message->getPosition(),
        ] ) );
    }

    protected function addToast( string $type, string $message, string $title = "", int $duration = 5000, string $position = 'top-right' ) : void
    {
        $this->addFlash( $type, $message );
//        $this->addToastMessage( new FlashMessage( $message, $title, $type, $duration, $position ) );
    }


}