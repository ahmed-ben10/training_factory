<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        public function ledenGegevens()
        {
            return $this->render('leden/leden_gegevens.html.twig', [
                'page_name' => 'leden_gegevens',
            ]);
        }
}
