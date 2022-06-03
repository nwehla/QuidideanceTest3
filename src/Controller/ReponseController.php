<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/reponse")
 */
class ReponseController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function getSlugger(Reponse $reponse) : string{
        $slug = mb_strtolower($reponse->getTitre() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }
    /**
     * @Route("/", name="app_reponse_index", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('reponse/reponse_index.html.twig', [
                'reponses' => $reponseRepository->findAll(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_reponse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReponseRepository $reponseRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $reponse = new Reponse();
            $form = $this->createForm(ReponseType::class, $reponse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // on slug
                $reponse->setSlug($this->getSlugger($reponse))
                    ->setDatecreation(new \DateTimeImmutable());
                $reponseRepository->add($reponse, true);

                return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reponse/new.html.twig', [
                'reponse' => $reponse,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{slug}", name="app_reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('reponse/show.html.twig', [
                'reponse' => $reponse,
                'slug' => $reponse->getSlug(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{slug}/modifier", name="app_reponse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(ReponseType::class, $reponse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reponse->setSlug($this->getSlugger($reponse))
                    ->setDatemiseajour(new \DateTimeImmutable());
                $reponseRepository->add($reponse, true);

                return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reponse/edit.html.twig', [
                'reponse' => $reponse,
                'form' => $form,
                'reponse' => $reponse->getSlug(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_reponse_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
                $reponseRepository->remove($reponse, true);
            }

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }
}
