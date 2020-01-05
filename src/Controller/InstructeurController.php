<?php


namespace App\Controller;


use App\Form\BezoekerFormType;
use App\Form\BezoekerWijzigFormType;
use App\Form\InstructorWijzigFormType;
use App\Form\LessenFormType;
use App\Repository\InstructorRepository;
use App\Repository\LessonRepository;
use App\Repository\MemberRepository;
use App\Repository\RegistrationRepository;
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

    public function instructeurLessenPlannen(Request $request, EntityManagerInterface $em, InstructorRepository $instructorRepository) {
        $lessenForm=$this->createForm(LessenFormType::class);
        $lessenForm->handleRequest($request);
        if($lessenForm->isSubmitted() && $lessenForm->isValid()){
            $data = $lessenForm->getData();
            $data->setInstructor($instructorRepository->findOneBy(['person'=>$this->getUser()]));
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
     * @Route("/instructeur/lessen/update/{id}",name="instructeur_lessen_update")
     */

    public function instructeurLessenUpdate(Request $request, EntityManagerInterface $em, LessonRepository $lessonRepository, $id ) {
        $lessen =  $lessonRepository->find($id);
        $lessenForm=$this->createForm(LessenFormType::class,$lessen);
        $lessenForm->handleRequest($request);
        if($lessenForm->isSubmitted() && $lessenForm->isValid()){
            $data = $lessenForm->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Les gewijzigd!');
            return $this->redirectToRoute('instructeur_lessen_beheer');
        }
        return $this->render('instructeur/instructeur_lessen_update.html.twig',[
            'page_name'=>'instructeur_lessen_update',
            'lessonForm'=>$lessenForm->createView()
        ]);
    }

    /**
     * @Route("/instructeur/lessen/delete/{id}",name="instructeur_lessen_delete")
     */

    public function instructeurLesDelete(LessonRepository $lessonRepository, EntityManagerInterface $em, $id){
        $les = $lessonRepository->find($id);
        $em->remove($les);
        $em->flush();
        $this->addFlash('success','De les is succesvol verwijderd!');
        return $this->redirectToRoute('instructeur_lessen_beheer');
    }

    /**
     * @Route("/instructeur/lessen/beheer",name="instructeur_lessen_beheer")
     */

    public function instructeurLessenBeheer(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository, InstructorRepository $instructorRepository) {
        $lessen = $lessonRepository->findBy(['instructor'=> $instructorRepository->findOneBy(['person'=>$this->getUser()])]);
        return $this->render('instructeur/instructeur_lessen_beheer.html.twig',[
            'page_name'=>'instructeur_lessen_beheer',
            'lessen'=>$lessen,
            'registrations'=>$registrationRepository
        ]);
    }

    /**
     * @Route("/instructeur/lessen/beheer/deelnemerlijst/{id}",name="instructeur_lessen_beheer_deelnemerlijst")
     */

    public function instructeurLessenDeelnemerlijst(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository, $id) {
        $lessen = $lessonRepository->find($id);
        $members = $registrationRepository->getMemberCount($lessen);
        return $this->render('instructeur/instructeur_lessen_beheer_deelnemerlijst.html.twig',[
            'page_name'=>'instructeur_lessen_beheer',
            'regMembers'=>$members,
            'registrations' => $registrationRepository
        ]);
    }

    /**
     * @Route("/instructeur/lessen/beheer/deelnemerlijst/{id}/deelnemer/{memId}",name="instructeur_lessen_beheer_deelnemerlijst_deelnemer")
     */

    public function instructeurLessenDeelnemerlijstSetPayment(EntityManagerInterface $em, MemberRepository $memberRepository, RegistrationRepository $registrationRepository, $id, $memId) {
        $member = $memberRepository->find($memId);
        $reg = $registrationRepository->findOneBy(['member'=>$member]);
        $payment = $reg->getPayment();
        $reg->setPayment(!$payment);
        $em->persist($reg);
        $em->flush();
        return $this->redirectToRoute('instructeur_lessen_beheer_deelnemerlijst',['id'=>$id]);
    }

    /**
     * @Route("/instructeur/wijzigen",name="instructeur_profiel_wijzigen")
     */

    public function instructeurProfielWijzige(Request $request, EntityManagerInterface $em) {
        $instructorWijzig=$this->createForm(InstructorWijzigFormType::class, $this->getUser());
        $instructorWijzig->handleRequest($request);
        if($instructorWijzig->isSubmitted() && $instructorWijzig->isValid()){
            $data = $instructorWijzig->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Instructeur gewijzigd');
            return $this->redirectToRoute('instructeur_home');
        }
        return $this->render('instructeur/instructeur_lessen_plannen.html.twig',[
            'page_name'=>'instructeur_profiel_wijzigen',
            'lessonForm'=>$instructorWijzig->createView()
        ]);
    }
}