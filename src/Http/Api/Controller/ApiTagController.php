<?php

namespace App\Http\Api\Controller;

use App\Domain\Tag\Entity\Tag;
use App\Domain\Tag\Repository\TagRepository;
use App\Http\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTagController extends AbstractController
{
    #[Route( '/admin/tags/new/{title}', name: 'admin_tag_new', methods: ['POST'] )]
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