<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/",name="bezoeker_home")
     */
    public function homepage()
    {
        return $this->render('bezoeker/home.html.twig', ['page_name' => 'bezoeker_home']);
    }

    /**
     * @Route("/bezoeker_trainings_aanbod",name="bezoeker_trainings_aanbod")
     */
    public function trainingsAanbod()
    {
        return $this->render('bezoeker/trainings_aanbod.html.twig', ['page_name' => 'bezoeker_trainings_aanbod']);

    }

    /**
     * @Route("/registreren",name="bezoeker_registreren")
     */
    public function registreren()
    {
        return $this->render('bezoeker/bezoeker_registreren.html.twig', ['page_name' => 'bezoeker_registreren']);
    }

    /**
     * @Route("/gedrags_regels",name="bezoeker_regels")
     */
    public function gedragsRegels(){
        return $this->render('bezoeker/bezoeker_regels.html.twig', ['page_name' => 'bezoeker_regels']);
    }

    /**
     * @Route("/bezoeker_contact",name="bezoeker_contact")
     */
    public function contact()
    {
        return $this->render('bezoeker/contact.html.twig', ['page_name' => 'bezoeker_contact']);

    }
}