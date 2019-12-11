<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Person;
use App\Form\BezoekerFormType;
use App\Repository\MemberRepository;
use App\Repository\PersonRepository;
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
        public function ledenInschrijvingOverzicht()
        {
            return $this->render('leden/leden_inschrijven_overzicht.html.twig', [
                'page_name' => 'leden_inschrijven_overzicht',
            ]);
        }


    /**
     * @Route("/leden/lessen/inschrijven", name="leden_lessen_inschrijven")
     */
        public function ledenLessenInschrijven()
        {
            return $this->render('leden/leden_lessen_inschrijven.html.twig', [
                'page_name' => 'leden_lessen_inschrijven',
            ]);
        }

    /**
     * @Route("/leden/gegevens", name="leden_gegevens")
     */
        public function ledenGegevens(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, PersonRepository $personRepository, MemberRepository $memberRepository)
        {
            $user = $this->getUser();
            $person = $personRepository->find($user->getId());
            $bezoekerForm = $this->createForm(BezoekerFormType::class, $person);
            $member = $memberRepository->findOneBy([ 'person'=>$person]);
            $bezoekerForm['street']->setData($member->getStreet());
            $bezoekerForm['postal_code']->setData($member->getPostalCode());
            $bezoekerForm['city']->setData($member->getPlace());
            $bezoekerForm->handleRequest($request);
            if ($bezoekerForm->isSubmitted() && $bezoekerForm->isValid()) {
                $data = $bezoekerForm->getData();
//            dd($data);
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
