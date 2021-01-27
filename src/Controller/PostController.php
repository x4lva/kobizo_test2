<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="create_post")
     */
    public function createPost(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle('New car!');
        $post->setContent("Bla bla bla bla");
        $post->setStatus('created');
        $post->setCreatedAt(new \DateTime("now"));

        $entityManager->persist($post);

        $entityManager->flush();

        return new Response('Saved new product with id '.$post->getId());
    }
}
