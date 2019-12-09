<?php


namespace App\Controller;


use App\Form\LessenFormType;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InstructeurController extends AbstractController
{
/**
 * @Route("/instructeur",name="instructeur_home")
 */

    public function instructeurHome() {
        return $this->render('instructeur/instructeur_home.html.twig',[
            'page_name'=>'instructeur_home'
        ]);
    }

    /**
     * @Route("/instructeur/lessen/plannen",name="instructeur_lessen_plannen")
     */

    public function instructeurLessenPlannen(Request $request, EntityManagerInterface $em) {
        $lessenForm=$this->createForm(LessenFormType::class);
        $lessenForm->handleRequest($request);
        if($lessenForm->isSubmitted() && $lessenForm->isValid()){
            $data = $lessenForm->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Nieuwe les gemaakt!');
            return $this->redirectToRoute('instructeur_lessen_beheer');
        }
        return $this->render('instructeur/instructeur_lessen_plannen.html.twig',[
            'page_name'=>'instructeur_lessen_plannen',
            'lessonForm'=>$lessenForm->createView()
        ]);
    }

    /**
     * @Route("/instructeur/lessen/beheer",name="instructeur_lessen_beheer")
     */

    public function instructeurLessenBeheer(LessonRepository $lessonRepository) {
        $lessen = $lessonRepository->findAll();
        return $this->render('instructeur/instructeur_lessen_beheer.html.twig',[
            'page_name'=>'instructeur_lessen_beheer',
            'lessen'=>$lessen
        ]);
    }
}