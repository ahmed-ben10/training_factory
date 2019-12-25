<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Person;
use App\Entity\Registration;
use App\Form\BezoekerFormType;
use App\Form\BezoekerWIjzigFormType;
use App\Repository\LessonRepository;
use App\Repository\MemberRepository;
use App\Repository\PersonRepository;
use App\Repository\RegistrationRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Object_;
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
        public function ledenLesseninschrijvingen(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository, MemberRepository $memberRepository)
        {
            $dateTime = new \DateTime('now');

            return $this->render('leden/leden_lessen_inschrijven.html.twig', [
                'page_name' => 'leden_lessen_inschrijven',
                'lessen'=>$lessonRepository->findBy(['date'=> new \DateTime(date('Y-m-d'))]),
                'lessenRepo'=>$lessonRepository,
                'registrations' => $registrationRepository,
                'date' =>  $dateTime->format('Y-m-d'),
                'later' => false,
                'member'=>$memberRepository->findOneBy([ 'person'=>$this->getUser()])
            ]);
        }

    /**
     * @Route("/leden/lessen/inschrijvingen/date/{date} ", name="leden_lessen_inschrijven_datum")
     */
    public function ledenLesseninschrijvingenDatum(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository,MemberRepository $memberRepository,$date)
    {
        return $this->render('leden/leden_lessen_inschrijven.html.twig', [
            'page_name' => 'leden_lessen_inschrijven',
            'registrations' => $registrationRepository,
            'lessen' => $lessonRepository->findBy(['date' => new \DateTime($date)]),
            'lessenRepo' => $lessonRepository,
            'date' => $date,
            'later' => false,
            'member'=>$memberRepository->findOneBy([ 'person'=>$this->getUser()])
        ]);
    }

    /**
     * @Route("/leden/lessen/inschrijvingen/later ", name="leden_lessen_inschrijven_datum_later")
     */
    public function ledenLesseninschrijvingenLater(LessonRepository $lessonRepository, RegistrationRepository $registrationRepository, MemberRepository $memberRepository)
    {
        $lessen = null;
        return $this->render('leden/leden_lessen_inschrijven.html.twig', [
            'page_name' => 'leden_lessen_inschrijven',
            'lessen'=>$lessonRepository->findBy(['date' => new \DateTime('now')]),
            'registrations'=>$registrationRepository,
            'lessenRepo'=>$lessonRepository,
            'date' => null,
            'later' => true,
            'member'=>$memberRepository->findOneBy([ 'person'=>$this->getUser()])
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


            $bezoekerForm = $this->createForm(BezoekerWIjzigFormType::class, $person);

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
