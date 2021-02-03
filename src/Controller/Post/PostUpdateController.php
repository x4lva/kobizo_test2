<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Post;
use App\Form\MetasType;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostUpdateController extends BaseController
{


    // Post update page
    /**
     * @Route("/post/postupdate/{id}", name="post_update", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function postUpdate(int $id){

        $post = new Post();

        $forRender = parent::renderDefault();
        $forRender["title"] = "Post Update";

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('App:Post')->find($id);

        $form = $this->createForm(PostType::class, $post);

        $forRender["post"] = $post;
        $forRender["form"] = $form->createView();

        return $this->render("post/postupdate.html.twig", $forRender);
    }


    // Post update POST request
    /**
     * @Route("/post/postupdate/{id}", name="post_update_request", methods={"POST"})
     * @param Post $post
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postUpdateRequest(Post $post, Request $request){

        $status = "error";

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime("now"));

            $em->persist($post);

            try {
                // Updating post
                $em->flush();
                $status = "success";
            }catch (\Exception $e){
                $status = $e;
            }

        }else{
            $status = "error";
        }

        // Sending data to ajax function
        return new JsonResponse($status);

    }

}