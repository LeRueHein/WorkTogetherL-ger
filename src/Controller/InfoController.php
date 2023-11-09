<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Reservation;
use App\Entity\Unit;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/info', name: 'app_info')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $pack = $entityManager->getRepository(Pack::class)->findAll();

        $user = $this->getUser();
        $reservations = $user->getReservations();


        return $this->render('info/index.html.twig', [
            'controller_name' => 'InfoController',
            'reservations' => $reservations,
            'user' => $user
        ]);
    }
}
