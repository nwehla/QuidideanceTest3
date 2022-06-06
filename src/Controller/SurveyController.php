<?php

namespace App\Controller;

use App\Entity\Survey;
use DateTimeImmutable;
use App\Form\SurveyType;
use App\Repository\SurveyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/survey")
 */
class SurveyController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function getSlugger(Survey $survey) : string{
        $slug = mb_strtolower($survey->getTitre() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }
    /**
     * @Route("/", name="app_survey_index", methods={"GET"})
     */
    public function index(SurveyRepository $surveyRepository,Request $request,PaginatorInterface $pagi): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $surveys = $pagi->paginate(
                $surveyRepository->findWithPagination(),
                $request->query->getInt('page',1),7
                        
            );
            return $this->render('survey/survey_index.html.twig', [
                'surveys' => $surveys,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_survey_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SurveyRepository $surveyRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $survey = new Survey();
            $form = $this->createForm(SurveyType::class, $survey);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // on slug
                $survey->setSlug($this->getSlugger($survey))
                    ->setDatecreation(new \DateTimeImmutable());

                $surveyRepository->add($survey, true);

                return $this->redirectToRoute('app_survey_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('survey/new.html.twig', [
                'survey' => $survey,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{slug}", name="app_survey_show", methods={"GET"})
     */
    public function show(Survey $survey): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('survey/show.html.twig', [
                'survey' => $survey,
                'slug' => $survey->getSlug(),
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }        
    }

    /**
     * @Route("/{slug}/modifier", name="app_survey_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Survey $survey, SurveyRepository $surveyRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(SurveyType::class, $survey);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                 // on slug
                 $survey->setSlug($this->getSlugger($survey))
                 ->setDatemiseajour(new \DateTimeImmutable());

                $surveyRepository->add($survey, true);

                return $this->redirectToRoute('app_survey_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('survey/edit.html.twig', [
                'survey' => $survey,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_survey_delete", methods={"POST"})
     */
    public function delete(Request $request, Survey $survey, SurveyRepository $surveyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$survey->getId(), $request->request->get('_token'))) {
            $surveyRepository->remove($survey, true);
        }

        return $this->redirectToRoute('app_survey_index', [], Response::HTTP_SEE_OTHER);
    }
}
