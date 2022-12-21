<?php

namespace App\Controller;

use App\Entity\Series;
use App\Form\SeriesType;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeriesController extends AbstractController
{
    public function __construct(private SeriesRepository $seriesRepository, private EntityManagerInterface $entityManager)
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
        $seriesForm = $this->createForm(type: SeriesType::class, data: new Series());
        return $this->renderForm('series/form.html.twig', compact(var_name: 'seriesForm'));
    }

    #[Route('series/create', name: "app_add_series", methods: ['POST'])]
    public function addSeries(Request $request): Response
    {
        $series = new Series();
        $seriesForm = $this->createForm(type: SeriesType::class, data: $series)
            ->handleRequest($request);

        if (!$seriesForm->isValid()) { //validação garantida pelo servidor impedindo invalidação forçada pelo browser
            return $this->renderForm(view: 'series/form.html.twig', parameters: compact('seriesForm'));
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
        return $this->render('series/form.html.twig', compact(var_name: 'series'));
    }

    #[Route('/series/edit/{series}', name: 'app_store_series_changes', methods: ['PATCH'])]
    public function storeSeriesChanges(Series $series, Request $request): Response
    {
        $series->setName($request->request->get('name'));
        $this->addFlash(type: 'success', message: "Série \"{$series->getName()}\" editada com sucesso");
        $this->entityManager->flush();

        return new RedirectResponse(url: '/series');
    }
}
