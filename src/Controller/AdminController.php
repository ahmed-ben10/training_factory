<?php


namespace App\Controller;


use App\Entity\Instructor;
use App\Form\AdminLidFormType;
use App\Form\AdminTrainingenFormType;
use App\Form\BezoekerWijzigFormType;
use App\Form\InstructorFormType;
use App\Form\InstructorWijzigFormType;
use App\Repository\InstructorRepository;
use App\Repository\LessonRepository;
use App\Repository\MemberRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function home()
    {
        return $this->render('admin/admin_home.html.twig', ['page_name' => 'admin_home']);
    }

    /**
     * @Route("/admin/instucteurs", name="admin_instucteurs")
     */
    public function instucteurs(InstructorRepository $instructorRepository , RegistrationRepository $registrationRepository,LessonRepository $lessonRepository)
    {
        return $this->render('admin/admin_instucteurs.html.twig', [
            'page_name' => 'admin_instucteurs',
            'instucteurs'=>$instructorRepository->findAll(),
            'lessenRepo'=>$lessonRepository,
            'registrationRepo'=>$registrationRepository

        ]);
    }

    /**
     * @Route("/admin/instucteurs/omzet/{id}", name="admin_instucteurs_omzet")
     */
    public function instucteursOmzet(InstructorRepository $instructorRepository , RegistrationRepository $registrationRepository,LessonRepository $lessonRepository, $id){
        $months = array();
        for($m=1; $m<=12; ++$m){
            array_push($months,['month'=> date('F', mktime(0, 0, 0, $m, 1)), 'month_num'=>$m]);
        }
        return $this->render('admin/admin_instucteurs_omzet.html.twig', [
            'page_name' => 'admin_instucteurs',
            'instucteur'=>$instructorRepository->find($id),
            'lessenRepo'=>$lessonRepository,
            'registrationRepo'=>$registrationRepository,
            'months'=> $months
        ]);
    }

    /**
     * @Route("/admin/instucteurs/omzet/month/", name="admin_instucteurs_omzet_month")
     */
    public function instucteursOmzetMonth(Request $request,InstructorRepository $instructorRepository , RegistrationRepository $registrationRepository,LessonRepository $lessonRepository){
        if($request->request->get('month')){
            //make something curious, get some unbelieveable data
            $omzet = 0;
            foreach ( $lessonRepository->findBy(['instructor' => $instructorRepository->findBy(['id' => $request->request->get('id')])]) as $les){
                if($les->getDate()->format("m") == $request->request->get('month') ) {
                    foreach ($registrationRepository->findBy(['lesson' => $les]) as $reg) {
                        if ($reg->getPayment()) {
                            $omzet += $les->getTraining()->getCosts();
                        }
                    }
                }
            }
            $arrData = [
                'month_num' => $request->request->get('month') ,
                'omzet'=> number_format($omzet,2)
            ];
            return new JsonResponse($arrData);
        }

        return $this->render('leden/leden_home.html.twig');
    }

    /**
     * @Route("/admin/instucteurs/details/{id}", name="admin_instucteurs_details")
     */
    public function instucteursLessen(InstructorRepository $instructorRepository, EntityManagerInterface $em , Request $request, $id)
    {
        $instr = $instructorRepository->find($id);
        $instructorForm = $this->createForm(InstructorWijzigFormType::class, $instr->getPerson());
        $instructorForm->handleRequest($request);
        if($instructorForm->isSubmitted() && $instructorForm->isValid()){
            $data = $instructorForm->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Instructuer is gewijzgd!');
            return $this->redirectToRoute('admin_instucteurs');
        }
        return $this->render('admin/admin_instucteurs_details.html.twig', [
            'page_name' => 'admin_instucteurs',
            'instucteur'=>$instr,
            'instrForm'=>$instructorForm->createView()
        ]);
    }

    /**
     * @Route("/admin/instucteurs/delete/{id}", name="admin_instucteurs_delete")
     */
    public function instucteursLessenDelete(InstructorRepository $instructorRepository, EntityManagerInterface $em , $id)
    {
        $instructor= $instructorRepository->find($id);
        foreach ($instructor->getLessons() as $lesson) {
            foreach ( $lesson->getRegistrations() as $reg) {
                $em->remove($reg);
            }
            $em->remove($lesson);
        }
        $em->remove($instructor);
        $em->flush();

        $this->addFlash('success','Instucteur verwijderd!');

        return $this->redirectToRoute('admin_instucteurs');
    }

    /**
     * @Route("/admin/instucteurs/create", name="admin_instucteurs_toevoegen")
     */
    public function instucteursNew(InstructorRepository $instructorRepository, EntityManagerInterface $em , Request $request)
    {
        $instructorForm = $this->createForm(InstructorFormType::class);
        $instructorForm->handleRequest($request);
        $instr = new Instructor();
        if($instructorForm->isSubmitted() && $instructorForm->isValid()){
            $data = $instructorForm->getData();
            $data->setRoles(["ROLE_INSTRUCTEUR"]);
            $instr->setSalary($instructorForm['salary']->getData());
            $instr->setHiringDate(new \DateTime());
            $em->persist($data);
            $em->persist($instr);
            $em->flush();
            $this->addFlash('success','Instructuer is gemaakt!');
            return $this->redirectToRoute('admin_instucteurs');
        }
        return $this->render('admin/admin_instucteurs_toevoegen.html.twig', [
            'page_name' => 'admin_instucteurs',
            'instrForm'=>$instructorForm->createView()
        ]);
    }

    /**
     * @Route("/admin/leden", name="admin_leden")
     */
    public function leden(MemberRepository $memberRepository)
    {
        return $this->render('admin/admin_leden.html.twig', [
            'page_name' => 'admin_leden',
            'leden'=> $memberRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/leden/disabled/{lid}", name="admin_leden_wijzig_disabled")
     */
    public function ledenDisabled(MemberRepository $memberRepository, EntityManagerInterface $em ,$lid)
    {
        $member = $memberRepository->find($lid);
        $member->setDisabled(!$member->getDisabled());
        $em->persist($member);
        $em->flush();
        $this->addFlash('success','Lid gewijzigd!');
        return $this->redirectToRoute('admin_leden');
    }


    /**
     * @Route("/admin/leden/details/{lid}", name="admin_leden_details")
     */
    public function ledenDetails(Request $request,EntityManagerInterface $em , MemberRepository $memberRepository,$lid)
    {
        $member = $memberRepository->find($lid);
        $lidForm = $this->createForm(AdminLidFormType::class, $member->getPerson());
        $lidForm['street']->setData($member->getStreet());
        $lidForm['postal_code']->setData($member->getPostalCode());
        $lidForm['city']->setData($member->getPlace());
        $lidForm->handleRequest($request);


        if ($lidForm->isSubmitted() && $lidForm->isValid()) {
            $data = $lidForm->getData();
            $member
                ->setStreet( $lidForm['street']->getData())
                ->setPostalCode( $lidForm['postal_code']->getData())
                ->setPlace( $lidForm['city']->getData())
            ;
            $em->persist($data);
            $em->persist($member);
            $em->flush();
            $this->addFlash('success','Lid gewijzigd!');
            return $this->redirectToRoute('admin_leden');

        }

        return $this->render('admin/admin_leden_details.html.twig', [
            'page_name' => 'admin_leden',
            'lid'=> $member,
            'lidForm' => $lidForm->createView()
        ]);
    }
    /**
     * @Route("/admin/leden/details/{lid}/{les}",name="admin_leden_details_wijzig_payment")
     */

    public function adminLedenDetailsWijzigPayment(EntityManagerInterface $em, MemberRepository $memberRepository, RegistrationRepository $registrationRepository, $lid,$les) {
        $member = $memberRepository->find($lid);
        $reg = $registrationRepository->findOneBy(['member'=>$member,'lesson'=>$les]);
        $payment = $reg->getPayment();
        $reg->setPayment(!$payment);
        $em->persist($reg);
        $em->flush();
        return $this->redirectToRoute('admin_leden_details',['lid'=>$lid]);
    }

    /**
     * @Route("/admin/trainingen", name="admin_trainingen")
     */
    public function trainingen(TrainingRepository $trainingRepository)
    {
        $trainingen = $trainingRepository->findAll();
        return $this->render('admin/admin_trainingen.html.twig', ['page_name' => 'admin_trainingen', 'trainingen' => $trainingen]);
    }

    /**
     * @Route("/admin/trainingen/delete/{id}", name="admin_trainingen_delete")
     */
    public function trainingenDelete(TrainingRepository $trainingRepository, EntityManagerInterface $em, $id){
        $training = $trainingRepository->find($id);
        $em->remove($training);
        $em->flush();
        $this->addFlash('success','Trraining verwijderd!');
        return $this->redirectToRoute('admin_trainingen');
    }

    /**
     * @Route("/admin/trainingen/create", name="admin_trainingen_create")
     */
    public function trainingenCreate(EntityManagerInterface $em, Request $request){
        $trainingForm = $this->createForm(AdminTrainingenFormType::class);
        $trainingForm->handleRequest($request);
        if($trainingForm->isSubmitted() && $trainingForm->isValid()) {
            $img = $trainingForm['image_dir']->getData();
            $img->move($this->getParameter('training_img'), $img->getClientOriginalName());

            $data = $trainingForm->getData();
            $data->setImageDir($img->getClientOriginalName());
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Training toegevoegd!');
            return $this->redirectToRoute('admin_trainingen');
        }
        return $this->render('admin/admin_trainingen_create.html.twig',[
            'page_name'=>'admin_trainingen',
            'trainingForm'=>$trainingForm->createView()
        ]);

    }

    /**
     * @Route("/admin/trainingen/update/{id}", name="admin_trainingen_update")
     */
    public function trainingenUpdate(EntityManagerInterface $em, TrainingRepository $trainingRepository, Request $request, $id){
        $training = $trainingRepository->find($id);
        $trainingForm = $this->createForm(AdminTrainingenFormType::class, $training);
        $trainingForm->handleRequest($request);
        if($trainingForm->isSubmitted() && $trainingForm->isValid()) {
            $img = $trainingForm['image_dir']->getData();
            $data= null;
            if($img){
                $img->move($this->getParameter('training_img'), $img->getClientOriginalName());
                $data = $trainingForm->getData();
                $data->setImageDir($img->getClientOriginalName());
            } else{
                $data = $trainingForm->getData();
                $data->setImageDir($training->getImageDir());
            }
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Training gewijzigdg0!');
            return $this->redirectToRoute('admin_trainingen');
        }
        return $this->render('admin/admin_trainingen_create.html.twig',[
            'page_name'=>'admin_trainingen',
            'trainingForm'=>$trainingForm->createView()
        ]);
    }

}