<?php

namespace App\Controller\Admin;

use App\Entity\ImageRealisation;
use App\Entity\Realisation;
use App\Form\RealisationType;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/realisation')]
class RealisationController extends AbstractController
{
    #[Route('/', name: 'app_admin_realisation_index', methods: ['GET'])]
    public function index(RealisationRepository $realisationRepository): Response
    {
        return $this->render('admin/realisation/index.html.twig', [
            'realisations' => $realisationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_realisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RealisationRepository $realisationRepository, EntityManagerInterface $entityManager): Response
    {
        $realisation = new Realisation();
        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadImages = $form->get('images')->getData();

            foreach ($uploadImages as $uploadImage) {
                $fileName = md5(uniqid()) . '.' . $uploadImage->guessExtension();
                $uploadImage->move(
                    $this->getParameter('upload_images_directory'),
                    $fileName
                );

                $image = new ImageRealisation();
                $image->setName($fileName);
                $realisation->addImage($image);

                $entityManager->persist($image);
            }

            $realisation->setCreatedAt(new \DateTimeImmutable());

            $realisationRepository->save($realisation, true);

            return $this->redirectToRoute('app_admin_realisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/realisation/new.html.twig', [
            'realisation' => $realisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_realisation_show', methods: ['GET'])]
    public function show(Realisation $realisation): Response
    {
        return $this->render('admin/realisation/show.html.twig', [
            'realisation' => $realisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_realisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Realisation $realisation, RealisationRepository $realisationRepository): Response
    {
        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $realisationRepository->save($realisation, true);

            return $this->redirectToRoute('app_admin_realisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/realisation/edit.html.twig', [
            'realisation' => $realisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_realisation_delete', methods: ['POST'])]
    public function delete(Request $request, Realisation $realisation, RealisationRepository $realisationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$realisation->getId(), $request->request->get('_token'))) {
            $realisationRepository->remove($realisation, true);
        }

        return $this->redirectToRoute('app_admin_realisation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete-image', name: 'app_admin_realisation_delete_image', methods: ['DELETE'])]
    public function deleteImage( ImageRealisation $imageRealisation, Request $request , EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$imageRealisation->getId(), $data['_token'])) {
            $name = $imageRealisation->getName();
            unlink($this->getParameter('upload_images_directory').'/'.$name);

            $entityManager->remove($imageRealisation);
            $entityManager->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

//    public function deleteImage(Request $request, Realisation $realisation, RealisationRepository $realisationRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$realisation->getId(), $request->request->get('_token'))) {
//            $realisationRepository->remove($realisation, true);
//        }
//
//        return $this->redirectToRoute('app_admin_realisation_index', [], Response::HTTP_SEE_OTHER);
//    }
}
