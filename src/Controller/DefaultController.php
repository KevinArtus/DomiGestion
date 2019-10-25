<?php

namespace App\Controller;

use App\Repository\ReunionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'acceuil avec la liste des réunions à venir
     *
     * @Route("/", name="dashboard_index", methods={"GET"})
     */
    public function index(ReunionRepository $reunionRepository)
    {
        $reunions = $reunionRepository->createQueryBuilder('r')
            ->where('r.date >= :date')
            ->andWhere('r.user = :user')
            ->setParameter('date', date('Y-m-d H:i:s'))
            ->setParameter('user', $this->getUser())
            ->getQuery()
            ->getResult();

        return $this->render('default/dashboard.html.twig', [
            'reunions' => $reunions
        ]);
    }
}