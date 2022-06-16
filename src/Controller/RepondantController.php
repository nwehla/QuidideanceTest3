<?php

namespace App\Controller;

use App\Entity\Repondant;
use App\Form\RepondantType;
use App\Repository\RepondantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/repondant")
 */
class RepondantController extends AbstractController
{
    /**
     * @Route("/", name="app_repondant_index", methods={"GET"})
     */
    public function index(RepondantRepository $repondantRepository): Response
    {
        return $this->render('repondant/index.html.twig', [
            'repondants' => $repondantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_repondant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RepondantRepository $repondantRepository): Response
    {
        $repondant = new Repondant();
        $form = $this->createForm(RepondantType::class, $repondant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repondantRepository->add($repondant, true);

            return $this->redirectToRoute('app_repondant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repondant/new.html.twig', [
            'repondant' => $repondant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_repondant_show", methods={"GET"})
     */
    public function show(Repondant $repondant): Response
    {
        return $this->render('repondant/show.html.twig', [
            'repondant' => $repondant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_repondant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Repondant $repondant, RepondantRepository $repondantRepository): Response
    {
        $form = $this->createForm(RepondantType::class, $repondant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repondantRepository->add($repondant, true);

            return $this->redirectToRoute('app_repondant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('repondant/edit.html.twig', [
            'repondant' => $repondant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_repondant_delete", methods={"POST"})
     */
    public function delete(Request $request, Repondant $repondant, RepondantRepository $repondantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repondant->getId(), $request->request->get('_token'))) {
            $repondantRepository->remove($repondant, true);
        }

        return $this->redirectToRoute('app_repondant_index', [], Response::HTTP_SEE_OTHER);
    }
}
