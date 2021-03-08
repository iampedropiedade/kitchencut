<?php

namespace App\Controller;

use App\Entity\InvoiceHeader;
use App\Form\InvoiceHeaderType;
use App\Form\InvoiceSearchType;
use App\Repository\InvoiceHeaderRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invoice/header")
 */
class InvoiceHeaderController extends AbstractController
{
    protected const PER_PAGE = 10;

    /**
     * @Route("/", name="invoice_header_index", methods={"GET"})
     */
    public function index(InvoiceHeaderRepository $invoiceHeaderRepository, Request $request): Response
    {
        $form = $this->createForm(InvoiceSearchType::class);
        $form->handleRequest($request);

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($invoiceHeaderRepository->findAllQb($form->getData())
            ));
        $pagerfanta
            ->setMaxPerPage(self::PER_PAGE)
            ->setCurrentPage($request->query->get('page', 1));

        return $this->render('invoice_header/index.html.twig', [
            'invoice_headers' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
            'searchForm' => $form->createView(),
            'searchQuery' => $request->query->get('invoice_search', ''),
        ]);
    }

    /**
     * @Route("/new", name="invoice_header_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $invoiceHeader = new InvoiceHeader();
        $form = $this->createForm(InvoiceHeaderType::class, $invoiceHeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invoiceHeader);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_header_index');
        }

        return $this->render('invoice_header/new.html.twig', [
            'invoice_header' => $invoiceHeader,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="invoice_header_show", methods={"GET"})
     */
    public function show(InvoiceHeader $invoiceHeader): Response
    {
        return $this->render('invoice_header/show.html.twig', [
            'invoice_header' => $invoiceHeader,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="invoice_header_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InvoiceHeader $invoiceHeader): Response
    {
        $form = $this->createForm(InvoiceHeaderType::class, $invoiceHeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoice_header_index');
        }

        return $this->render('invoice_header/edit.html.twig', [
            'invoice_header' => $invoiceHeader,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="invoice_header_delete", methods={"DELETE"})
     */
    public function delete(Request $request, InvoiceHeader $invoiceHeader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoiceHeader->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($invoiceHeader);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invoice_header_index');
    }
}
