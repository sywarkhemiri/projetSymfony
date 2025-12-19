<?php
// src/Controller/Admin/CommandeController.php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commandes')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'commande_list', methods: ['GET'])]
    public function list(CommandeRepository $commandeRepository): Response
    {
        // Récupérer toutes les commandes avec leurs relations
        $commandes = $commandeRepository->findAllWithDetails();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/delete/{id}', name: 'commande_delete', methods: ['GET'])]
    public function delete(Commande $commande, EntityManagerInterface $em): Response
    {
        // Doctrine s'occupe automatiquement de supprimer les CommandeProduit
        // grâce à cascade: ['remove'] et orphanRemoval: true
        $em->remove($commande);
        $em->flush();

        $this->addFlash('success', 'Commande supprimée avec succès.');
        return $this->redirectToRoute('admin_commande_list');
    }

    #[Route('/details/{id}', name: 'commande_details', methods: ['GET'])]
    public function details(Commande $commande): Response
    {
        return $this->render('commande/index.html.twig', [
            'commande' => $commande,
        ]);
    }
}
