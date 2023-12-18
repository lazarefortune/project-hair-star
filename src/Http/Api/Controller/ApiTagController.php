<?php

namespace App\Http\Api\Controller;

use App\Domain\Tag\Entity\Tag;
use App\Domain\Tag\Repository\TagRepository;
use App\Http\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTagController extends AbstractController
{
    #[Route( '/admin/tags/new', name: 'admin_tag_new', methods: ['POST'] )]
    public function index( Request $request, TagRepository $tagRepository ) : Response
    {
        // get name from body request post
        $data = json_decode( $request->getContent(), true );
        $title = $data['name'] ?? null;

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