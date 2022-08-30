<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    #[Route('/admin/bien', name: 'app_admin_bien')]
    public function index(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->findAll();
        return $this->render('admin/bien/bien.html.twig', [
            'controller_name' => 'BienController',
            'biens'=>$biens
        ]);
    }

    #[Route('/admin/bien/supprimer/{id}', name: 'app_admin_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(BienRepository $bienRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $bien = $bienRepository->find($id);
        $entityManager->remove($bien);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Votre bien à été supprimé !"
        );

        return $this->redirectToRoute('app_admin_bien');
    }

    #[Route('/admin/ajouter', name: 'app_admin_ajouter')]
    #[Route('/admin/modifier/{id}', name: 'app_admin_modifier', requirements: ['id' => '\d+'])]
    public function ajouter(BienRepository $bienRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $message = "";
        if($request->attributes->get('_route') == "app_admin_ajouter"){
            $bien = new Bien();
            $message = "Votre bien à été ajouté !";
        } else{
            $bien = $bienRepository->find($id);
            $message = "Votre bien à été modifié !";
        }

        $form = $this->createForm(BienType::class, $bien);
        $bien->getPrixBien();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $bien = $form->getData();
            $bien->setPrix($bien->getPrixBien());

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($bien);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_bien');
        }


        return $this->render('admin/bien/editerBien.html.twig', [
            'controller_name' => 'BienController',
            'form' => $form->createView()
        ]);


    }
}
