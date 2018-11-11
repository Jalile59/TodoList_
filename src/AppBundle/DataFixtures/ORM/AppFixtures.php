<?php



use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;


class AppFixtures extends Fixture
{
    
    
    public function load(ObjectManager $manager)
    {   
      
        $password = "123";
        $roles[0] = 'ROLE_ADMIN';
        
        $user = new User();
        
        $user->setUsername('Admin');
        $user->setEmail('Admin@admin.com');
        $user->setRoles($roles);
        
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $password);
       
        $user->setPassword($encoded);

       
        
        $manager->persist($user);
        
        $quantityTask = 300;
        
        for ($i = 0; $i<$quantityTask; $i++){
            $task = new Task();
            $task->setTitle('Tache n°'.$i);
            $task->setContent('une tache à accomplir !.');
            
            $manager->persist($task);
            
            
        }
        

        $manager->flush();
    }
}