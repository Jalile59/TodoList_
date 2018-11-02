<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;



class TaskControllerTest extends WebTestCase
{
    
    
    private $client     = null;
    private $login      = 'jalile';
    private $email      = 'test_email_';
    private $password   = '123';
    
    
    public function setUp()
    {
        $this->client = static::createClient();
        
        $this->login = $this->login;
        $this->email = $this->login;
    }
    

    public function testLogin(){
        
        $client = $this->client;
        
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();
        
        $form['_username'] = $this->login;
        $form['_password'] = $this->password;
        
        $crawler = $client->submit($form);
        
        $crawler = $client->followRedirect();
        
        $this->client = $client;
        
       
        //echo $client->getResponse()->getContent();
        
        
        
        $this->assertSame('Consulter la liste des tâches à faire', $crawler->filter('.btn-info')->text());
        
        return $client;
        
    }
    
    public function testlisteAction(){
        
       $client = $this->testLogin();
       
      
       
       $crawler = $client->request('GET', '/tasks');
       
      
       
       //echo $client->getResponse()->getContent();
       
       $this->assertSame('Créer une tâche', $crawler->filter('.pull-right.btn-info')->text());
       
    }
    
    public function testCreateTask(){
        
        $client = $this->testLogin();
        
        $crawler = $client->request('GET', '/tasks/create');
        
        
        $form = $crawler->selectButton('Ajouter')->form();
        
        $form['task[title]'] = 'title_from_test_PHPUNIT';
        $form['task[content]'] = 'phpunit !';
        
        $crawler = $client->submit($form);
        
        $crawler = $client->followRedirect();
        
        //echo $client->getResponse()->getContent();
        
        $this->assertSame('Créer une tâche', $crawler->filter('.pull-right.btn-info')->text());
        
    }
    

    
    public function testTeskToggle() {
        
        $client= $this->testLogin();
        
        $crawler = $client->request('GET', '/tasks/7/toggle');
        
        $crawler = $client->followRedirect();
        
        //echo $client->getResponse()->getContent();
        
        $this->assertSame('Superbe !', $crawler->filter('strong')->text());
    }
    
    public function testEditTask(){
        
        $client = $this->testLogin();
        
        $crawler = $client->request('GET', '/tasks/7/edit');
        
        $form = $crawler->selectButton('Modifier')->form();
        
        $form['task[title]'] = 'title_from_test_PHPUNIT_modifier';
        $form['task[content]'] = 'phpunit !';
        
        $crawler = $client->submit($form);
        
        $crawler = $client->followRedirect();
        
        
       // echo $client->getResponse()->getContent();
        
        $this->assertSame('Créer une tâche', $crawler->filter('.pull-right.btn-info')->text());
        
        
    }
    
    public function testDeletedTask(){
        
        $client = $this->testLogin();
        
        $crawler = $client->request('GET', '/tasks/42/delete');
        
        $crawler = $client->followRedirect();
        
        //echo $client->getResponse()->getContent();
        
        $this->assertSame('Superbe !', $crawler->filter('strong')->text());
        
    }
    
    public function testEntityTask(){
        
        $task = new Task();
        
        $task->setTitle('un titre');
        $task->setContent('du contenut');
        $task->setIsDone(TRUE);
        
        
        $this->assertSame(true, $task->getIsDone());
        
    }

}
