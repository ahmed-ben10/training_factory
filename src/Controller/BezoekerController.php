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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/trainingsaanbod",name="bezoeker_trainings_aanbod")
     */
    public function trainingsAanbod(TrainingRepository $trainingen)
    {
        return $this->render('bezoeker/trainings_aanbod.html.twig', ['page_name' => 'bezoeker_trainings_aanbod', 'trainingen' => $trainingen->findAll()]);

    }

    /**
     * @Route("/registreren",name="bezoeker_registreren")
     */
    public function registreren(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $person = new Person();
        $bezoekerForm = $this->createForm(BezoekerFormType::class, $person);
        $bezoekerForm->handleRequest($request);
        if ($bezoekerForm->isSubmitted() && $bezoekerForm->isValid()) {
            $data = $bezoekerForm->getData();
            $encoded = $encoder->encodePassword($person, $person->getPassword());
            $data->setPassword($encoded);
            $member = new Member();
            $member
                ->setStreet($bezoekerForm['street']->getData())
                ->setPostalCode($bezoekerForm['postal_code']->getData())
                ->setPlace($bezoekerForm['city']->getData())
                ->setPerson($data)
            ;
            $data->setRoles(['ROLE_MEMBER']);
            $em->persist($data);
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute('leden_lessen_inschrijvingen');
            

        }
        return $this->render('bezoeker/bezoeker_registreren.html.twig', ['page_name' => 'bezoeker_registreren', 'bezoekerForm' => $bezoekerForm->createView()]);
    }

    /**
     * @Route("/regels",name="bezoeker_regels")
     */
    public function gedragsRegels()
    {
        return $this->render('bezoeker/bezoeker_regels.html.twig', ['page_name' => 'bezoeker_regels']);
    }

    /**
     * @Route("/contact",name="bezoeker_contact")
     */
    public function contact()
    {
        return $this->render('bezoeker/contact.html.twig', ['page_name' => 'bezoeker_contact']);

    }
}