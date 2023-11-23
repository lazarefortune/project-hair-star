<?php

namespace App\Controller\Admin;

use App\Entity\ImageRealisation;
use App\Entity\Realisation;
use App\Form\RealisationType;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/realisations', name: 'realisation_' )]
#[IsGranted( 'ROLE_ADMIN' )]
class AdminRealisationController extends AbstractController
{
    #[Route( '/', name: 'index', methods: ['GET'] )]
    public function index( RealisationRepository $realisationRepository ) : Response
    {

//        $this->denyAccessUnlessGranted( 'can_manage_roles' );

        $realisations = $realisationRepository->findBy(
            [],
            ['dateRealisation' => 'DESC']
        );

        return $this->render( 'admin/realisation/index.html.twig', [
            'realisations' => $realisations,
        ] );
    }

    #[Route( '/new', name: 'new', methods: ['GET', 'POST'] )]
    public function new( Request $request, RealisationRepository $realisationRepository, EntityManagerInterface $entityManager ) : Response
    {
        $realisation = new Realisation();
        $form = $this->createForm( RealisationType::class, $realisation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->uploadImagesForRealisation( $form, $realisation, $entityManager );

            $realisation->setCreatedAt( new \DateTimeImmutable() );

            $realisationRepository->save( $realisation, true );

            $this->addToast( 'success', 'La réalisation a bien été ajoutée' );
            return $this->redirectToRoute( 'app_admin_realisation_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/realisation/new.html.twig', [
            'realisation' => $realisation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'show', methods: ['GET'] )]
    public function show( Realisation $realisation ) : Response
    {
        return $this->render( 'admin/realisation/show.html.twig', [
            'realisation' => $realisation,
        ] );
    }

    #[Route( '/{id}/edit', name: 'edit', methods: ['GET', 'POST'] )]
    public function edit( Request $request, Realisation $realisation, RealisationRepository $realisationRepository, EntityManagerInterface $entityManager ) : Response
    {
        $form = $this->createForm( RealisationType::class, $realisation );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->uploadImagesForRealisation( $form, $realisation, $entityManager );
            $realisationRepository->save( $realisation, true );

            $this->addToast( 'success', 'La réalisation ' . $realisation->getId() . ' a bien été modifiée' );
            return $this->redirectToRoute( 'app_admin_realisation_show', ['id' => $realisation->getId()], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( 'admin/realisation/edit.html.twig', [
            'realisation' => $realisation,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'delete', methods: ['POST'] )]
    public function delete( Request $request, Realisation $realisation, RealisationRepository $realisationRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $realisation->getId(), $request->request->get( '_token' ) ) ) {
            $realisationRepository->remove( $realisation, true );
        }

        return $this->redirectToRoute( 'app_admin_realisation_index', [], Response::HTTP_SEE_OTHER );
    }

    #[Route( '/{id}/delete-image', name: 'delete_image', methods: ['DELETE'] )]
    public function deleteImage( ImageRealisation $imageRealisation, Request $request, EntityManagerInterface $entityManager ) : JsonResponse
    {
        $data = json_decode( $request->getContent(), true );

        if ( $this->isCsrfTokenValid( 'delete' . $imageRealisation->getId(), $data['_token'] ) ) {
            $name = $imageRealisation->getName();
            unlink( $this->getParameter( 'realisation_img_dir' ) . '/' . $name );

            $entityManager->remove( $imageRealisation );
            $entityManager->flush();

            return new JsonResponse( ['success' => 1] );
        } else {
            return new JsonResponse( ['error' => 'Token Invalide'], 400 );
        }
    }

    #[Route( '/{id}/delete-images', name: 'delete_images', methods: ['POST'] )]
    public function deleteImagesForRealisation( Realisation $realisation, EntityManagerInterface $entityManager ) : void
    {
        // si le formulaire est soumis
        // on supprime les images
        // on supprime les entités ImageRealisation
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param Realisation $realisation
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function uploadImagesForRealisation( \Symfony\Component\Form\FormInterface $form, Realisation $realisation, EntityManagerInterface $entityManager ) : void
    {
        $uploadImages = $form->get( 'images' )->getData();

        foreach ( $uploadImages as $uploadImage ) {
            $fileName = md5( uniqid() ) . '.' . $uploadImage->guessExtension();
            $uploadImage->move(
                $this->getParameter( 'realisation_img_dir' ),
                $fileName
            );

            $image = new ImageRealisation();
            $image->setName( $fileName );
            $realisation->addImage( $image );

            $entityManager->persist( $image );
        }
    }

}
