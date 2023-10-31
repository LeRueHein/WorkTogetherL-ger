<?php

namespace App\Controller;

use App\Entity\Rack;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RackController extends AbstractController
{
    #[Route('/rack', name: 'app_rack')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {

        $pagination = $paginator->paginate(
            $entityManager->getRepository(Rack::class)->paginationQuery(),
            $request->query->get('page', 1),
            5
        );


        return $this->render('rack/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/rack/{id}', name: 'app_rack_information')]
    public function rack(EntityManagerInterface $entityManager, INT $id): Response
    {

        $rack = $entityManager->getRepository(Rack::class)->find($id);


        return $this->render('rack/info.html.twig', [
            'controller_name' => 'RackController',
            'rack' => $rack
        ]);
    }
}
