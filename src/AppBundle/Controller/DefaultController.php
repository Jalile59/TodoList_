<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        
        $tools = $this->get('service.tools');
        
        $check = $tools->checktaskOpheline(); // affecte les taches non attachées à l'utilisateur anonyme.
        

        if($check["taskOrph"])
        {
            $this->addFlash('success', "TodoList a détecté  ".$check["nombreAffect"]." taches non attaché à un utilisateur, ils ont était attribuéent à l'utilisateur Anonyme ");
            
        }
        
        
        return $this->render('default/index.html.twig');
    }
}
