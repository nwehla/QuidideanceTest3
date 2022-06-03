<?php

namespace App\Controller;

use App\Entity\Reponsequestion;
use App\Form\ReponsequestionType;
use App\Repository\ReponsequestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/reponse")
 */
class ReponsequestionController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function getSlugger(Reponsequestion $reponsequestion) : string{
        $slug = mb_strtolower($reponsequestion->getTitre() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }
    
    /**
     * @Route("/", name="app_reponse_index", methods={"GET"})
     */
    public function index(ReponsequestionRepository $reponsequestionRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('reponsequestion/reponse_index.html.twig', [
                'reponsequestion' => $reponsequestionRepository->findAll(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_reponse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReponsequestionRepository $reponsequestionRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $reponsequestion = new Reponsequestion();
            $form = $this->createForm(ReponsequestionType::class, $reponsequestion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // on slug
                $reponsequestion->setSlug($this->getSlugger($reponsequestion))
                    ->setDatecreation(new \DateTimeImmutable());
                $reponsequestionRepository->add($reponsequestion, true);

                return $this->redirectToRoute('app_reponsequestion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reponsequestion/new.html.twig', [
                'reponsequestion' => $reponsequestion,
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
    public function show(Reponsequestion $reponsequestion): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('reponsequestion/show.html.twig', [
                'reponsequestion' => $reponsequestion,
                'reponsequestion' => $reponsequestion->getSlug(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{slug}/modifier", name="app_reponse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponsequestion $reponsequestion, ReponsequestionRepository $reponsequestionRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(ReponsequestionType::class, $reponsequestion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reponsequestionRepository->add($reponsequestion, true);

                return $this->redirectToRoute('app_reponsequestion_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reponsequestion/edit.html.twig', [
                'reponsequestion' => $reponsequestion,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_reponse_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponsequestion $reponsequestion, ReponsequestionRepository $reponsequestionRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            if ($this->isCsrfTokenValid('delete'.$reponsequestion->getId(), $request->request->get('_token'))) {
                $reponsequestionRepository->remove($reponsequestion, true);
            }

            return $this->redirectToRoute('app_reponsequestion_index', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }
}
