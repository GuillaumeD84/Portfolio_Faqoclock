<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Question;
use AppBundle\Entity\Tag;

class QuestionController extends Controller
{
    /**
     * Permet à un utilisateur connecter d'ajouter un vote pour une question
     *
     * @Route("/question/{id}/addvote", name="question_addvote", requirements={"id":"\d+"})
     * @ParamConverter("question", class="AppBundle:Question")
     */
    public function questionAddVoteAction(Question $question, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();

            $user->addVoteQuestion($question);

            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'message' => 'okok',
            ]);
        }

        return $this->redirectToRoute('question_show', array('id' => $question->getId()));
    }

    /**
     * Affiche la liste des questions relatives à un tag
     *
     * @Route("/question/tag/{slug}", name="question_by_tag", requirements={"slug":"[a-z0-9-]+"})
     */
    public function questionByTagAction(EntityManagerInterface $em, $slug)
    {
        $tag = $em->getRepository(Tag::class)->findOneBy([
            'slug' => $slug
        ]);

        $questionByTag = $em->getRepository(Question::class)->findByTag($tag);

        return $this->render('question/question_by_tag.html.twig', [
            'questions' => $questionByTag,
            'tag' => $tag,
        ]);
    }
}
