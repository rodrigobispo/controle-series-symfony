<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Series;
use App\Entity\Episode;
use App\Form\SeriesType;
use App\DTO\SeriesCreateFromInput;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeriesController extends AbstractController
{
    public function __construct(private SeriesRepository $seriesRepository,
        private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/series', name: 'app_series', methods: ['GET'])]
    public function seriesList(Request $request): Response
    {
        $seriesList = $this->seriesRepository->findAll();

        return $this->render('series/index.html.twig', [
            'seriesList' => $seriesList,
        ]);
    }


    #[Route('series/create', name: 'app_series_form', methods: ['GET'])]
    public function addSeriesForm(): Response
    {
        $seriesForm = $this->createForm(type: SeriesType::class, data: new SeriesCreateFromInput());
        return $this->renderForm('series/form.html.twig', compact(var_name: 'seriesForm'));
    }


    #[Route('series/create', name: "app_add_series", methods: ['POST'])]
    public function addSeries(Request $request): Response
    {
        $input = new SeriesCreateFromInput();
        $seriesForm = $this->createForm(type: SeriesType::class, data: $input)
            ->handleRequest($request);

        if (!$seriesForm->isValid()) { //validação garantida pelo servidor impedindo invalidação forçada pelo browser
            return $this->renderForm(view: 'series/form.html.twig', parameters: compact('seriesForm'));
        }

        $series = new Series($input->seriesName);
        for ($i = 0; $i < $input->seasonsQuantity; $i++) {
            $season = new Season($i);
            for ($j = 0; $j < $input->episodesPerSeason; $j++) {
                $season->addEpisode(new Episode($j));
            }
            $series->addSeason($season);
        }

        $this->addFlash(type: 'success', message: "Série \"{$series->getName()}\" adicionada com sucesso");

        $this->seriesRepository->save($series, flush: true);
        return new RedirectResponse('/series');
    }


    #[Route('/series/delete/{id}', name: 'app_delete_series', methods: ['DELETE'])]
    public function deleteSeries(int $id, Request $request): Response
    {
        $this->seriesRepository->removeById($id);
        $session = $request->getSession();
        $this->addFlash(type: 'success', message: "Série removida com sucesso");

        return new RedirectResponse(url: '/series');
    }


    #[Route('/series/edit/{series}', name: 'app_edit_series_form', methods: ['GET'])]
    public function editSeriesForm(Series $series): Response
    {
        $seriesForm = $this->createForm(
                type: SeriesType::class,
                data: new SeriesCreateFromInput($series->getName()),
                options: ['is_edit' => true]);

        return $this->renderForm('series/form.html.twig', compact('seriesForm', 'series'));
    }


    #[Route('/series/edit/{series}', name: 'app_store_series_changes', methods: ['PATCH'])]
    public function storeSeriesChanges(Series $series, Request $request): Response
    {
        $seriesForm = $this->createForm(type: SeriesType::class, data: $series, options: ['is_edit' => true]);
        $seriesForm->handleRequest($request);

        if (!$seriesForm->isValid()) {
            return $this->renderForm('series/form.html.twig', compact('seriesForm', 'series'));
        }

        $this->addFlash(type: 'success', message: "Série \"{$series->getName()}\" editada com sucesso");
        $this->entityManager->flush();

        return new RedirectResponse(url: '/series');
    }
}
