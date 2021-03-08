<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Form\SearchType;
use App\Repository\LocationRepository;
use App\Repository\InvoiceHeaderRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController
{
    protected const PER_PAGE = 10;

    /**
     * @Route("/", name="location_index", methods={"GET"})
     */
    public function index(LocationRepository $locationRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($locationRepository->findAllQb($form->getData())
        ));
        $pagerfanta
            ->setMaxPerPage(self::PER_PAGE)
            ->setCurrentPage($request->query->get('page', 1));

        return $this->render('location/index.html.twig', [
            'locations' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
            'searchForm' => $form->createView(),
            'searchQuery' => $request->query->get('search', ''),
        ]);
    }

    /**
     * @Route("/new", name="location_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location, InvoiceHeaderRepository $invoiceHeaderRepository): Response
    {
        $invoicesSummary = $invoiceHeaderRepository->findByLocationGroupedByStatus($location);
        return $this->render('location/show.html.twig', [
            'location' => $location,
            'invoicesSummary' =>$invoicesSummary
        ]);
    }

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Location $location): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Location $location): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_index');
    }
}
