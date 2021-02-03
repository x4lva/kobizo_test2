<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Metas;
use App\Form\MetasType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostViewController extends BaseController
{

    // Getting data about current post
    /**
     * @Route("/post/postview/{id}", name="post_view", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function postView(int $id){

        $form = $this->createForm(MetasType::class);


        $forRender = parent::renderDefault();
        $forRender["title"] = "Post View";

        $em = $this->getDoctrine()->getManager();


        $post = $em->getRepository('App:Post')->find($id);
        $metas = $em->getRepository('App:Metas')->findBy(["post"=>$post->getId()]);

        $forRender["post"] = $post;
        $forRender["form"] = $form->createView();
        $forRender["metas"] = $metas;


        return $this->render("post/postview.html.twig", $forRender);
    }

    // Create meta form
    /**
     * @Route("/post/postview/createmeta/{id}", name="post_view_request", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function postViewPost(Request $request, int $id){

        $meta = new Metas();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(MetasType::class, $meta);
        $form->handleRequest($request);

        $status = "error";

        if ( $form->isSubmitted() && $form->isValid() ){

            $post = $em->getRepository('App:Post')->find($id);
            $meta->setPost($post);

            $em->persist($meta);

            try {
                // Adding new meta to database
                $em->flush();
                $status = "success";
            } catch (\Exception $e) {}

        }else{
            $status = "error";
        }

        // Sending data to ajax function
        return new JsonResponse($status);

    }

}