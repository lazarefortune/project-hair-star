<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route( '/roles', name: 'roles_index' )]
    public function allRoles( RoleRepository $roleRepository ) : Response
    {
        return $this->render( 'admin/settings/roles/index.html.twig', [
            'roles' => $roleRepository->findAll(),
        ] );
    }

    #[Route( '/roles/ajout', name: 'roles_create' )]
    #[IsGranted( 'can_manage_roles' )]
    public function createRole( RoleRepository $roleRepository, Request $request ) : Response
    {
        $roleForm = $this->createForm( RoleType::class );
        $roleForm->handleRequest( $request );

        if ( $roleForm->isSubmitted() && $roleForm->isValid() ) {
            $role = $roleForm->getData();
            $roleRepository->save( $role, true );

            $this->addFlash( 'success', 'Le rôle a bien été ajouté' );
            return $this->redirectToRoute( 'app_admin_settings_roles_index' );
        }

        return $this->renderForm( 'admin/settings/roles/new.html.twig', [
            'form' => $roleForm,
        ] );
    }

    #[Route( '/roles/{id}/modification', name: 'roles_edit', methods: ['GET', 'POST'] )]
    #[IsGranted( 'can_manage_roles' )]
    public function editRole( Request $request, Role $role, RoleRepository $roleRepository ) : Response
    {
        $form = $this->createForm( RoleType::class, $role );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $role = $form->getData();
            $roleRepository->save( $role, true );

            $this->addFlash( 'success', 'Le rôle a bien été modifié' );
            return $this->redirectToRoute( 'app_admin_settings_roles_index' );
        }

        return $this->render( 'admin/settings/roles/edit.html.twig', [
            'role' => $role,
            'form' => $form,
        ] );
    }

    #[Route( '/roles/{id}/delete', name: 'roles_delete' )]
    #[IsGranted( 'can_manage_roles' )]
    public function deleteRole( RoleRepository $roleRepository ) : Response
    {
        return $this->render( 'admin/settings/roles/index.html.twig' );
    }


}