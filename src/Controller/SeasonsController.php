<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonsController extends AbstractController
{
    public function __construct(private SeasonRepository $repository)
    {
    }

    #[Route('/seasons/{seriesId}/seasons', name: 'app_seasons')]
    public function index(int $seriesId): Response
    {
        $seasons = $this->repository->findBy(['series' => $seriesId]);

        return $this->render('seasons/index.html.twig', [
            'seasons' => $seasons,
        ]);
    }
}
