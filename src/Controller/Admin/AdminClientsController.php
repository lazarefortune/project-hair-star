<?php

namespace App\Controller\Admin;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/clients', name: 'app_admin_clients_')]
class AdminClientsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserRepository $userRepository ): Response
    {
        $clients = $userRepository->findBy(
            // find by 'role_user' (to retrieve clients)
            [],
            ['createdAt' => 'DESC']
        );


        return $this->render( 'admin/clients/index.html.twig', [
            'clients' => $clients,
        ] );
    }
}
