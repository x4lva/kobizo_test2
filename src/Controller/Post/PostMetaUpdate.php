<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Metas;
use App\Form\MetasType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostMetaUpdate extends BaseController
{

    // Meta create page
    /**
     * @Route("/post/postmeta/{id}", name="meta_update", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function postUpdate(int $id){

        $meta = new Metas();

        $forRender = parent::renderDefault();
        $forRender["title"] = "Meta Update";

        $em = $this->getDoctrine()->getManager();

        $meta = $em->getRepository('App:Metas')->find($id);

        $form = $this->createForm(MetasType::class, $meta);

        $forRender["meta"] = $meta;
        $forRender["form"] = $form->createView();

        return $this->render("post/postmetaupdate.html.twig", $forRender);
    }


    // Meta create POST request
    /**
     * @Route("/post/postmeta/{id}", name="meta_update_request", methods={"POST"})
     * @param Metas $meta
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postUpdateRequest(Metas $meta, Request $request){

        $status = "error";

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(MetasType::class, $meta);
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $meta = $form->getData();

            $em->persist($meta);

            try {
                // Updating meta
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