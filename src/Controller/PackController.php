<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Rack;
use App\Entity\Reservation;
use App\Entity\TypeReservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackController extends AbstractController
{
    #[Route('/pack', name: 'app_pack')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $packs = $entityManager->getRepository(Pack::class)->findAll();

        return $this->render('pack/index.html.twig', [
            'controller_name' => 'PackController',
            'packs' => $packs

        ]);
    }

    #[Route('/Reservationpack/{id}', name: 'app_reservation_pack')]
    public function new(EntityManagerInterface $entityManager, Request $request, $id): Response
    {

        $reservation = new Reservation();


        $pack = $entityManager->getRepository(Pack::class)->find($id);

        $name = uniqid();
        $reservation->setCode($name);
        $reservation->setTypeReservation($entityManager->getRepository(TypeReservation::class)->find(1));
        $reservation->setPack($entityManager->getRepository(Pack::class)->find($id));
        $reservation->setUser($this->getUser());
        $reservation->setPrice($entityManager->getRepository(Pack::class)->find($id)->getPrice());
        $reservation->setStartDate(new \DateTime('now'));
        $interval = new \DateInterval('P1M');
        $reservation->setEndDate(Date_add(new \DateTime('now'), $interval));
        $reservation->setPercentage(0);


        $entityManager->persist($reservation);

        $entityManager->flush();

        return $this->redirectToRoute('app_info');


    }

}
