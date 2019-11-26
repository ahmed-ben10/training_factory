<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
    /**
     * @Route("/",name="app_home")
     */
    public function homepage(){
        return $this->render('home/home.html.twig');
    }
}