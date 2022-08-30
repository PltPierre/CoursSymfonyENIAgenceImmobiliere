<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Repository\AgentRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    #[Route('/admin/agent', name: 'app_admin_agent')]
    public function index(AgentRepository $agentRepository): Response
    {
        $agents = $agentRepository->findAll();
        return $this->render('admin/agent/agent.html.twig', [
            'controller_name' => 'AgentController',
            'agents' => $agents
        ]);
    }

    #[Route('/admin/agent/supprimer/{id}', name: 'app_admin_agent_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(AgentRepository $agentRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $agent = $agentRepository->find($id);
        $entityManager->remove($agent);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "l'agent a été supprimé !"
        );

        return $this->redirectToRoute('app_admin_agent');
    }

    #[Route('/admin/agent/ajouter', name: 'app_admin_agent_ajouter')]
    public function ajouter(AgentRepository $agentRepository,UserPasswordHasherInterface $hasher, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $message = "Votre agent a été ajouté !";
        $agent = new Agent();

        $form = $this->createForm(AgentType::class, $agent);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = $fileUploader->upload($photoFile);
                $agent->setPhoto($photoFileName);
            }
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $agent = $form->getData();
            $agent->setPassword($hasher->hashPassword($agent, $agent->getPassword()));
            $agent->setRoles(['ROLE_AGENT']);

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($agent);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_agent');
        }


        return $this->render('admin/agent/ajouterAgent.html.twig', [
            'controller_name' => 'AgentController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/agent/modifier/{id}', name: 'app_admin_agent_modifier', requirements: ['id' => '\d+'])]
    public function edit(AgentRepository $agentRepository, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, $id = null): Response
    {
        $agent = $agentRepository->find($id);
        $message = "Votre agent a été modifié !";

        $form = $this->createForm(AgentType::class, $agent,[
            'usePassword'=>false
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = $fileUploader->upload($photoFile);
                $agent->setPhoto($photoFileName);
            }
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $agent = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($agent);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_agent');
        }


        return $this->render('admin/agent/editerAgent.html.twig', [
            'controller_name' => 'AgentController',
            'form' => $form->createView()
        ]);
    }
}
