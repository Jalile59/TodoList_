<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends Controller
{
    /**
     * @Route("/users/{page}", name="user_list",requirements ={"page":"\d+"}))
     * 
     */
    public function listAction($page = 1)
    {
        $data = $this->getDoctrine()->getRepository(User::class)->getall_paginat($page);
        
        return $this->render('user/list.html.twig', ['users' => $data,
                                                      'page' => $page  
        ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
       
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    
    /**
     * 
     * @Route("/user/{id}/supp", name="user_supp")
     */
    
    public function suppUser($id)
    {
        $dataUser = $this->getDoctrine()->getRepository(User::class)->find($id);
        
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($dataUser);
        
        $manager->flush();
        
        $this->addFlash('success', "l'utilisateur a bien été supprimer.");
        
        return $this->redirectToRoute('user_list');
        
    }
}
