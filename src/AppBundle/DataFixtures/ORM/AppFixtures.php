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
            $task->setContent("Lorem Ipsum est un générateur de faux textes aléatoires. Vous choisissez le nombre de paragraphes, de mots ou de listes. Vous obtenez alors un texte aléatoire que vous pourrez ensuite utiliser librement dans vos maquettes.

Le texte généré est du pseudo latin et peut donner l'impression d'être du vrai texte.

Faux-Texte est une réalisation du studio de création de sites internet indépendant Prélude Prod.

Si vous aimez la photographie d'art et l'esprit zen, jetez un œil sur le site de ce photographe à Palaiseau, en Essonne (France).");
            
            $manager->persist($task);
            
            
        }
        

        $manager->flush();
    }
}