<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserRoleController extends AbstractController
{
//    #[Route( '/admin/roles', name: 'app_admin_roles' )]
//    public function index( RoleRepository $roleRepository ) : Response
//    {
//        return $this->render( 'admin/users/roles/index.html.twig', [
//            'roles' => $roleRepository->findAll(),
//        ] );
//    }
//
////    #[Route( '/admin/roles/{id}', name: 'app_admin_roles_show' )]
////    public function show( RoleRepository $roleRepository, int $id ) : Response
////    {
////        return $this->render( 'admin/users/roles/show.html.twig', [
////            'role' => $roleRepository->find( $id ),
////        ] );
////    }
////
//    #[Route( '/admin/roles/{id}/edit', name: 'app_admin_roles_edit', methods: ['GET', 'POST'] )]
//    public function edit( Request $request, Role $role, RoleRepository $roleRepository ) : Response
//    {
//
//        // check if the user has the permission to edit roles
////        $this->denyAccessUnlessGranted( 'can_manage_roles' );
//
//        $form = $this->createForm( RoleType::class, $role );
//        $form->handleRequest( $request );
//
//        if ( $form->isSubmitted() && $form->isValid() ) {
//            $role = $form->getData();
//            $roleRepository->save( $role, true );
//
//            $this->addFlash( 'success', 'Le rôle a bien été modifié' );
//            return $this->redirectToRoute( 'app_admin_roles' );
//        }
//
//        return $this->render( 'admin/users/roles/edit.html.twig', [
//            'role' => $role,
//            'form' => $form,
//        ] );
//    }
//
////
//    #[Route( '/admin/roles/{id}/delete', name: 'app_admin_roles_delete' )]
//    public function delete( Role $role, RoleRepository $roleRepository ) : Response
//    {
////        return $this->render( 'admin/users/roles/delete.html.twig', [
////            'role' => $roleRepository->find( $id ),
////        ] );
//    }
//
//    #[Route( '/admin/roles/new', name: 'app_admin_roles_create' )]
//    public function new( RoleRepository $roleRepository, Request $request ) : Response
//    {
//        $roleForm = $this->createForm( RoleType::class );
//        $roleForm->handleRequest( $request );
//
//        if ( $roleForm->isSubmitted() && $roleForm->isValid() ) {
//            $role = $roleForm->getData();
//            $roleRepository->save( $role, true );
//
//            $this->addFlash( 'success', 'Le rôle a bien été ajouté' );
//            return $this->redirectToRoute( 'app_admin_roles' );
//        }
//
////        dd( $roleForm );
//        return $this->renderForm( 'admin/users/roles/new.html.twig', [
//            'form' => $roleForm,
//        ] );
//    }
}
