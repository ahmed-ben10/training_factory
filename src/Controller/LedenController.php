<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Person;
use App\Entity\Registration;
use App\Form\BezoekerFormType;
use App\Repository\LessonRepository;
use App\Repository\MemberRepository;
use App\Repository\PersonRepository;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LedenController extends AbstractController
{
    /**
     * @Route("/leden", name="leden_home")
     */
        public function ledenHome()
    {
        return $this->render('leden/leden_home.html.twig', [
            'page_name' => 'leden_home',
        ]);
    }

    /**
     * @Route("/leden/lessen", name="leden_inschrijven_overzicht")
     */
        public function ledenInschrijvingOverzicht(RegistrationRepository $registrationRepository, MemberRepository $memberRepository, LessonRepository $lessonRepository)
        {
            $member = $memberRepository->findBy(['person'=>$this->getUser()]);
            $registrations = $registrationRepository->findBy(['member'=>$member]);
            return $this->render('leden/leden_inschrijven_overzicht.html.twig', [
                'page_name' => 'leden_inschrijven_overzicht',
                'myLessons'=>  $registrations,
                'registrations'=>$registrationRepository
            ]);
        }

        /**
         * @Route("/leden/lessen/uitschrijven/{id}", name="leden_lessen_uitschrijven")
         */
        public function ledenUitschrijven(EntityManagerInterface $em,RegistrationRepository $registrationRepository, MemberRepository $memberRepository, LessonRepository $lessonRepository, $id)
        {
            $registrations = $registrationRepository->find($id);
            $em->remove($registrations);
            $em->flush();
            return $this->redirectToRoute('leden_inschrijven_overzicht');
        }


    /**
     * @Route("/leden/lessen/inschrijvingen ", name="leden_lessen_inschrijvingen")
     */
        public function ledenLesseninschrijvingen(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository)
        {

            return $this->render('leden/leden_lessen_inschrijven.html.twig', [
                'page_name' => 'leden_lessen_inschrijven',
                'lessen'=>$lessonRepository->findAll(),
                'registrations'=>$registrationRepository,
            ]);
        }


    /**
     * @Route("/leden/lessen/inschrijven/{id}", name="leden_lessen_inschrijven")
     */
    public function ledenLessenInschrijven(LessonRepository $lessonRepository,MemberRepository $memberRepository, EntityManagerInterface $em, $id)
    {
        $registration = new Registration();
        $registration
            ->setLesson($lessonRepository->find($id))
            ->setMember($memberRepository->findOneBy(['person'=>$this->getUser()]))
        ;
        $em->persist($registration);
        $em->flush();
        return $this->redirectToRoute('leden_inschrijven_overzicht');
    }

    /**
     * @Route("/leden/gegevens", name="leden_gegevens")
     */
        public function ledenGegevens(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, PersonRepository $personRepository, MemberRepository $memberRepository)
        {
            $person = $this->getUser();


            $bezoekerForm = $this->createForm(BezoekerFormType::class, $person);

            $member = $memberRepository->findOneBy([ 'person'=>$person]);

            $bezoekerForm['street']->setData($member->getStreet());
            $bezoekerForm['postal_code']->setData($member->getPostalCode());
            $bezoekerForm['city']->setData($member->getPlace());
            if($bezoekerForm->isSubmitted())
            {
                dd($request);
            }
            $bezoekerForm->handleRequest($request);


            if ($bezoekerForm->isSubmitted() && $bezoekerForm->isValid()) {
                $data = $bezoekerForm->getData(); ;
                $encoded = $encoder->encodePassword($person, $person->getPassword());
                $data->setPassword($encoded);
                $member
                    ->setStreet($bezoekerForm['street']->getData())
                    ->setPostalCode($bezoekerForm['postal_code']->getData())
                    ->setPlace($bezoekerForm['city']->getData())
                    ->setPerson($data)
                ;
                $em->persist($data);
                $em->persist($member);
                $em->flush();

                return $this->redirectToRoute('leden_home');


            }

            return $this->render('leden/leden_gegevens.html.twig', [
                'page_name' => 'leden_gegevens',
                'bezoekerForm'=>$bezoekerForm->createView()
            ]);
        }
}
