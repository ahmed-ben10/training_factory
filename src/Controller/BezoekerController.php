<?php


namespace App\Controller;


use App\Form\BezoekerFormType;
use App\Repository\TrainingRepository;
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
    public function trainingsAanbod(TrainingRepository $trainingen)
    {
        return $this->render('bezoeker/trainings_aanbod.html.twig', ['page_name' => 'bezoeker_trainings_aanbod', 'trainingen'=>$trainingen->findAll()]);

    }

    /**
     * @Route("/registreren",name="bezoeker_registreren")
     */
    public function registreren()
    {
        $bezoekerForm =  $this->createForm(BezoekerFormType::class);
        return $this->render('bezoeker/bezoeker_registreren.html.twig', ['page_name' => 'bezoeker_registreren', 'bezoekerForm'=>$bezoekerForm->createView()]);
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