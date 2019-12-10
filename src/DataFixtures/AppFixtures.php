<?php

namespace App\DataFixtures;

use App\Entity\Instructor;
use App\Entity\Person;
use App\Entity\Training;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<10; $i++){
            $personAdmin = new Person();

            $personAdmin
                ->setEmailaddress('test' .$i . '@hotmail.com')
                ->setLoginname('test' . $i)
                ->setPassword('test' . $i)
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
                ->setPassword('in' . $i)
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