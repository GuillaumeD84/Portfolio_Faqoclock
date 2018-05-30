<?php

namespace AppBundle\Repository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function count()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(q.id) FROM AppBundle:Question q'
            )
            ->getResult();

        // On retourne le résultat contenu dans 2 arrays sous forme de int
        return (int)$result[0][1];
    }

    public function findByTag($tag)
    {
        $result = $this->getEntityManager()
            ->createQuery('
                SELECT q
                FROM AppBundle:Question q
                JOIN q.tags t
                WHERE t.id = :tagId
            ')->setParameter('tagId', $tag->getId())
            ->getResult();

        return $result;
    }
}

// createQuery('
//     SELECT q.*
//     FROM AppBundle:Question q
//     JOIN question_tag
//     WHERE q.id = question_tag.question_id
//     AND question_tag.tag_id = 4
// ')
