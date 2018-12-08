<?php

namespace AppBundle\Tools;

use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


class Tools extends EntityRepository
{
    
    private $em;
    
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        
    }
    
    public function checktaskOpheline()
    {
        
        
      
        
        $data = $this->em->getRepository(Task::class)->taskanonyme();
        
        $limit = count($data);
        
        if($limit > 0)
        {
            $user = $this->em->getRepository(User::class)->findOneByUsername('Anonyme');
            
            
            
            for ($i = 0; $i < $limit; $i++) 
            {
                
                $data[$i]->setUser($user);
                $this->em->flush();
                
            }
            return $tabR = ["taskOrph" => true, "nombreAffect" => $limit];
        }else{
            
            return  $tabR = ["taskOrph" => false];
        }
    
    }

}
