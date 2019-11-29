<?php


namespace App\Controller;


use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function leden()
    {
        return $this->render('admin/admin_leden.html.twig', ['page_name' => 'admin_leden']);
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
    public function trainingenDelete(TrainingRepository $trainingRepository, EntityManagerInterface $em, $id)
    {
        $training = $trainingRepository->find($id);
        $em->remove($training);
        $em->flush();
        return $this->redirectToRoute('admin_trainingen');

    }
}