<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/",name="app_bezoeker_home")
     */
    public function homepage()
    {
        return $this->render('bezoeker/home.html.twig', ['page_name' => 'app_bezoeker_home']);
    }

    /**
     * @Route("/registreren",name="app_bezoeker_registreren")
     */
    public function registreren()
    {
        return $this->render('bezoeker/bezoeker_registreren.html.twig', ['page_name' => 'app_bezoeker_registreren']);
    }

    /**
     * @Route("/app_bezoeker_trainings_aanbod",name="app_bezoeker_trainings_aanbod")
     */
    public function trainingsAanbod()
    {
        return $this->render('bezoeker/home.html.twig', ['page_name' => 'app_bezoeker_trainings_aanbod']);

    }

    /**
     * @Route("/app_bezoeker_contact",name="app_bezoeker_contact")
     */
    public function contact()
    {
        return $this->render('bezoeker/home.html.twig', ['page_name' => 'app_bezoeker_contact']);

    }
}