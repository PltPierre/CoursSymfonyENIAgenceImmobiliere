<?php

namespace App\Controller;

use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    #[Route('/bien', name: 'app_bien')]

    public function index(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->findAll();

        return $this->render('bien.html.twig', [
            'biens'=>$biens
        ]);

    }

    #[Route('/contact', name: 'app_contact')]

    public function contact(): Response
    {

        return $this->render("contact.html.twig");
    }

    #[Route('/bien/{id}', name: 'app_bien_info', requirements: ['id' => '\d+'])]
    public function infosBien(BienRepository $bienRepository, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $bien = $bienRepository->find($id);
        return $this->render("infoBien.html.twig",[
            'bien'=>$bien
        ]);
    }
}
