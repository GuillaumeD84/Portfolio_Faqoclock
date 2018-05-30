<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Question;
use AppBundle\Entity\Answer;

class MainController extends Controller
{
    /**
     * Page d'acceuil du site
     * Contient les 10 dernières questions créés
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(EntityManagerInterface $em)
    {
        // On va chercher les 10 premières questions
        $questions = $em->getRepository(Question::class)->findBy(
            [], ['createdAt' => 'DESC'], 10
        );

        // On récupère le nombre de question pour la pagination
        $questionCount = $em->getRepository(Question::class)->count();

        return $this->render('main/index.html.twig', [
            'questions' => $questions,
            'questionCount' => $questionCount,
        ]);
    }

    /**
     * Page 2 à ? des questions
     * Contient la suite des questions
     * Chaque page contient 10 questions maximum
     *
     * @Route("/page/{number}", name="page_list", requirements={"number":"^(?!(?:0|1)$)\d+$"})
     */
    public function pageAction(EntityManagerInterface $em, $number)
    {
        // On va chercher 10 questions avec un offset
        $questions = $em->getRepository(Question::class)->findBy(
            [], ['createdAt' => 'DESC'], 10, ($number-1)*10
        );

        // On récupère le nombre de question pour la pagination
        $questionCount = $em->getRepository(Question::class)->count();

        return $this->render('main/index.html.twig', [
            'questions' => $questions,
            'questionCount' => $questionCount,
        ]);
    }

    /**
     * Page d'une question
     * Contient les détails de la question et les réponses associées
     * Route gérant la création d'une réponse
     *
     * @Route("/question/{id}", name="question_show", requirements={"id":"\d+"})
     * @ParamConverter("question", class="AppBundle:Question")
     */
    public function showAction(Question $question, Request $request)
    {
        // On intègre un formulaire en dessous des réponses pour pouvoir poster une réponse
        $answer = new Answer();
        $form = $this->createForm('AppBundle\Form\AnswerType', $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On a mappé le corps de texte de la réponse lors du handleRequest()
            // Il nous faut définir des valeurs par défaut ainsi que l'auteur et la question concernés
            $answer->setCreatedAt(new \DateTime('now'));
            $answer->setIsBlocked(false);
            $answer->setAuthor($this->getUser());
            $answer->setQuestion($question);

            // On persiste en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();

            $this->addFlash(
                'success',
                'Answer successfully posted!'
            );

            return $this->redirectToRoute('question_show', array('id' => $question->getId()));
        }

        return $this->render('main/show.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
    * Formulaire de création d'une nouvelle question
    *
    * @Route("/question/new", name="question_new")
     */
    public function newQuestionAction(Request $request, EntityManagerInterface $em)
    {
        $question = new Question();
        $form = $this->createForm('AppBundle\Form\QuestionType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setCreatedAt(new \DateTime('now'));
            $question->setAuthor($this->getUser());

            // On persiste en base
            $em->persist($question);
            $em->flush();

            $this->addFlash(
                'success',
                'Question successfully created!'
            );

            return $this->redirectToRoute('question_show', array('id' => $question->getId()));
        }

        return $this->render('main/question_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * Page de profil de l'utilisateur
    *
    * @Route("/profile", name="user_profile")
     */
    public function profileAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('main/profile.html.twig');
    }
}
