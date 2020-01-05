<?php


namespace App\Controller;


use App\Form\AdminLidFormType;
use App\Form\AdminTrainingenFormType;
use App\Form\BezoekerWijzigFormType;
use App\Repository\MemberRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
    public function instucteurs()
    {
        return $this->render('admin/admin_instucteurs.html.twig', ['page_name' => 'admin_instucteurs']);
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
     * @Route("/admin/leden/details/{lid}/{les}",name="
     * ")
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