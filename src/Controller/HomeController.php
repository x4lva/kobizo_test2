<?php


namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     * */
    public function home(){
        $forRender = parent::renderDefault();
        $forRender["title"] = "Home";

        return$this->render("home.html.twig", $forRender);
    }
}