<?php

namespace App\DataFixtures;

use App\Entity\Instructor;
use App\Entity\Member;
use App\Entity\Person;
use App\Entity\Training;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<10; $i++){
            $personAdmin = new Person();
            $personAdmin
                ->setEmailaddress('test' .$i . '@hotmail.com')
                ->setLoginname('test' . $i)
                ->setPassword($this->passwordEncoder->encodePassword($personAdmin,'test' . $i))
                ->setFirstname('naam' . $i)
                ->setLastname('achternaam'. $i)
                ->setGender('Man')
                ->setRoles(['ROLE_ADMIN'])
                ->setDateofbirth(new \DateTime())
            ;
            $manager->persist($personAdmin);
            $manager->flush();

        }
        for ($i = 0; $i<10; $i++){
            $personInstructeur = new Person();
            $instructeur = new Instructor();
            $personInstructeur
                ->setEmailaddress('in' .$i . '@hotmail.com')
                ->setLoginname('in' . $i)
                ->setPassword($this->passwordEncoder->encodePassword($personInstructeur,'in' . $i))
                ->setFirstname('naam' . $i)
                ->setLastname('achternaam'. $i)
                ->setGender('Vrouw')
                ->setRoles(['ROLE_INSTRUCTEUR'])
                ->setDateofbirth(new \DateTime())
            ;
            $instructeur
                ->setPerson($personInstructeur)
                ->setHiringDate(new \DateTime())
                ->setSalary(100.22*$i);
            $manager->persist($personInstructeur);
            $manager->persist($instructeur);
            $manager->flush();

        }
        for ($i = 0; $i<10; $i++){
            $personMember = new Person();
            $member = new Member();
            $personMember
                ->setEmailaddress('me' .$i . '@hotmail.com')
                ->setLoginname('me' . $i)
                ->setPassword($this->passwordEncoder->encodePassword($personMember,'me' . $i))
                ->setFirstname('naam' . $i)
                ->setLastname('achternaam'. $i)
                ->setGender('Man')
                ->setRoles(['ROLE_MEMBER'])
                ->setDateofbirth(new \DateTime())
            ;
            $member
                ->setPerson($personMember)
                ->setPlace('Den Haag')
                ->setPostalCode('2593HD')
                ->setStreet('Pijnacker hordijckstraat 14')
            ;
            $manager->persist($personMember);
            $manager->persist($member);
            $manager->flush();

        }
        for ($i = 0; $i<10; $i++){
            $training = new Training();
            $training
                ->setNaam('Training'. $i)
                ->setDescription(''. $i)
                ->setCosts(1.3*$i)
                ->setDuration(new \DateTime())
                ->setImageDir('MMA.png')
            ;

            $manager->persist($training);
            $manager->flush();
        }
    }
}