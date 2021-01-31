<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Metas;
use App\Entity\Post;
use App\Form\MetasType;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostViewController extends BaseController
{

    /**
     * @Route("/post/postview", name="post_view", methods={"GET"})
     * @return Response
     */
    public function postView(){

        $form = $this->createForm(MetasType::class);


        $forRender = parent::renderDefault();
        $forRender["title"] = "Post View";

        $em = $this->getDoctrine()->getManager();


        $post = $em->getRepository('App:Post')->find($_GET['id']);
        $metas = $em->getRepository('App:Metas')->findBy(["post"=>$post->getId()]);

        $forRender["post"] = $post;
        $forRender["form"] = $form->createView();
        $forRender["metas"] = $metas;


        return $this->render("post/postview.html.twig", $forRender);
    }

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
                $em->flush();
                $status = "success";
            } catch (\Exception $e) {}

        }else{
            $status = "error";
        }

        return new JsonResponse($status);

    }

}