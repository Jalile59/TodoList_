<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class TaskController extends Controller
{
    /**
     * @Route("/tasks/{page}", name="task_list",requirements ={"page":"\d+"})
     */
    public function listAction($page = 1, Request $request)
    {
        $data = $request->server->all();
        $url = $data['REQUEST_URI'];
        
        //dump($url);
        
        $cache = new FilesystemAdapter();
        
        $cacheTask = $cache->getItem(md5($url));
     
        if(!$cacheTask->isHit()){
            
            $dataTask = $this->getDoctrine()->getRepository('AppBundle:Task')->getall_paginat($page);
            
            $cacheTask->set($dataTask);
            $cache->save($cacheTask);
        }else{
          //  dump("dans le cache");
            $dataTask = $cacheTask->get();
        }
        
        
        
        
        
        $user = $this->getUser();
        $roleuser = $user->getRoles();
        
        $data = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll();
        
        $nbredata = count($data);
        
        
        return $this->render('task/list.html.twig', ['tasks' => $dataTask,
                                'roleusercurrernt' => $roleuser[0],
                                'usercurrent'=> $user,
                                'page'=>$page,
                                'maxtask'=> ($nbredata/5)
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {

        
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getUser();
          
            $task->setUser($user);

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        // vérification user courant pour la suppression
        $usercurrent = $this->getUser();
        $roleusercurrent = $usercurrent->getRoles();
        $parentTask = $task->getUser();

        if($usercurrent != $parentTask or $roleusercurrent =='ROLE_ADMIN')
        {
            $this->addFlash('error', 'you cannot deleted this task !');
            
            return $this->redirectToRoute('homepage');
        }
        ///////////////////////////////////////////////////
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
