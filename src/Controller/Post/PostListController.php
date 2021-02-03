<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostListController extends BaseController
{

    // Posts list page
    /**
     * @Route("/post/postlist", name="post_list")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function postList(PaginatorInterface $paginator, Request $request){
        $forRender = parent::renderDefault();
        $forRender["title"] = "Post list";

        $em = $this->getDoctrine()->getManager();


        // Getting all posts ordered by date
        $query = $em->getRepository('App:Post')
            ->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();


        // Creating pagination
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        $forRender["pagination"] = $pagination;

        return $this->render("post/postlist.html.twig", $forRender);
    }

}