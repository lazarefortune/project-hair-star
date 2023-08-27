<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route( '/admin/api/tags/new/{title}', name: 'api_admin_tag_new', methods: ['POST'] )]
    public function index( string $title, TagRepository $tagRepository ) : Response
    {
        // check if tag already exists
        $tag = $tagRepository->findOneBy( ['name' => trim( strip_tags( $title ) )] );
        if ( $tag ) {
            return $this->json( [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ] );
        }

        $tag = new Tag();
        $tag->setName( trim( strip_tags( $title ) ) );
        $tagRepository->save( $tag, true );
        $id = $tag->getId();

        return $this->json( [
            'id' => $id,
            'name' => $title,
        ] );
    }
}
