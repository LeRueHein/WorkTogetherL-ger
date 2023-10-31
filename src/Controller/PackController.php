<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Rack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackController extends AbstractController
{
    #[Route('/pack', name: 'app_pack')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $pack = $entityManager->getRepository(Pack::class)->findAll();

        return $this->render('pack/index.html.twig', [
            'controller_name' => 'PackController',
            'pack' => $pack

        ]);
    }
}
