<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Answer;

class AnswerController extends Controller
{
    /**
     * Permet à un utilisateur connecter d'ajouter un vote pour une réponse
     *
     * @Route("/answer/{id}/addvote", name="answer_addvote", requirements={"id":"\d+"})
     * @ParamConverter("answer", class="AppBundle:Answer")
     */
    public function answerAddVoteAction(Answer $answer, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();

            $user->addVoteAnswer($answer);

            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'message' => 'okok',
            ]);
        }

        return $this->redirectToRoute('question_show', array('id' => $answer->getQuestion()->getId()));
    }
}
