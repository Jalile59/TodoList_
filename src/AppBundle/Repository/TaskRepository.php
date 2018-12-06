<?php

namespace AppBundle\Repository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getall_paginat($page)
    {
        $qbd = $this->createQueryBuilder('task')
        ->setFirstResult(($page-1)*5)
        ->setMaxResults(5)
        ->orderBy('task.createdAt', 'DESC');
        
        $data = $qbd->getQuery()->getResult();
        
        
        
        return $data;
        
    }
    
}
