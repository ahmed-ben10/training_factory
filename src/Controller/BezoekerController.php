<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/",name="app_bezoeker_home")
     */
    public function homepage(){
        return $this->render('bezoeker/home.html.twig',['page_name'=>'app_bezoeker_home']);
    }

    /**
     * @Route("/registreren",name="app_bezoeker_registreren")
     */
    public function registreren(){
        return $this->render('bezoeker/bezoeker_registreren.html.twig',['page_name'=>'app_bezoeker_registreren']);
    }
}