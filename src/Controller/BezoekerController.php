<?php


namespace App\Controller;


use App\Entity\Member;
use App\Entity\Person;
use App\Form\BezoekerFormType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/", name="bezoeker_home")
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
    public function registreren(EntityManagerInterface $em, Request $request)
    {

        $bezoekerForm =  $this->createForm(BezoekerFormType::class);
        $bezoekerForm->handleRequest($request);
        if($bezoekerForm->isSubmitted() && $bezoekerForm->isValid()){
            $data = $bezoekerForm->getData();
            $em->persist($data);
            $em->flush();


            $member->setStreet($data['straat']);
            $member->setPostalCode($data['stad']);
            $member->setPlace($data['email']);
            $member->setPerson($person);
            $em->persist($member);
            $em->flush();
            return $this->redirectToRoute('bezoeker_home');


        }
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