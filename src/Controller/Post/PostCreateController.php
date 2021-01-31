<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Post;
use App\Form\PostType;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCreateController extends BaseController
{


    /**
     * @Route("/post/create", name="post_create", methods={"GET"})
     */
    public function postCreate(){
        $forRender = parent::renderDefault();
        $forRender["title"] = "Post Create";

        $form = $this->createForm(PostType::class);


        $forRender["form"] = $form->createView();

        return $this->render("post/postcreate.html.twig", $forRender);
    }


    /**
     * @Route("/post/create", name="post_create_request", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function postCreatePost(Request $request){

        $post = new Post();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $status = "error";

        if ( $form->isSubmitted() && $form->isValid() ){

            $post->setStatus("Published");
            $post->setCreatedAt(new DateTime('now'));

            $em->persist($post);
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