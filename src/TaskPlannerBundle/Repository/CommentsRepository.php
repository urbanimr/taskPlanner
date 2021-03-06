<?php

namespace TaskPlannerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentsRepository extends EntityRepository
{
    public function findCommentsByTask($task){
        $comments = $this->getEntityManager()->createQuery(
            'SELECT C FROM TaskPlannerBundle:Comments C WHERE C.task = :task'
        )
            ->setParameter('task', $task)
            ->getResult();

    }
}
