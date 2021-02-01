<?php


namespace App\Controller\Post;


use App\Controller\BaseController;
use App\Entity\Metas;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class PostListController extends BaseController
{

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

        $query = $em->getRepository('App:Post')
            ->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        $forRender["pagination"] = $pagination;

        return $this->render("post/postlist.html.twig", $forRender);
    }

}