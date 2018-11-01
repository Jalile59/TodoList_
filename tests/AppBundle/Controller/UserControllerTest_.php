<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;



class UsertControllerTest extends WebTestCase
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
    
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        
        $this->assertSame(1, $crawler->selectButton('Se connecter')->count());
        
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
        
        // echo $client->getResponse()->getContent();
        
        $this->assertSame('Consulter la liste des tâches à faire', $crawler->filter('.btn-info')->text());
        
    }
    
    public function testInscription()
    {
        $client = $this->client;
        
        $crawler = $client->request('GET', '/users/create');
        
        $form = $crawler->selectButton('Ajouter')->form();
        
        $username = 'Jhondo '.uniqid();
        $password = '123';
        
        $this->login = $username;
        $this->password = $password;
        
        $form['user[username]'] =$username;
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = $password;
        $form['user[password][second]'] = $password;
        $form['user[email]'] = 'test@test.fr22'.uniqid();
        
        $client->submit($form);
        
        $crawler = $client->followRedirect(); //$crawler à jour à ce niveau apés la redirection.
        //echo $client->getResponse()->getContent();
        
     
        
        $this->assertSame('Superbe !', $crawler->filter('strong')->text()); 
        
    }
    
   
    
    public function testListeUser(){
        
        $client = $this->client;
        
        $crawler = $client->request('GET', '/users');
        
        //echo $client->getResponse()->getContent();
        
        $this->assertSame('Liste des utilisateurs', $crawler->filter('h1')->text());
        
    }
    
    public function testEditUser(){
        
        $client = $this->client;
        
        $crawler = $client->request('GET', '/users/87/edit');
        
        
        
        $this->assertSame(1, $crawler->filter('h1')->count());
        
        $form = $crawler->selectButton('Modifier')->form();
        
        $form['user[username]'] ='usermodifier12';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $form['user[password][first]'] = '123';
        $form['user[password][second]'] = '123';
        $form['user[email]'] = 'email@modifier12.com';
        
        $crawler = $client->submit($form);
       
        $crawler = $client->followRedirect();
        
        //echo $client->getResponse()->getContent();
        
        $this->assertSame('Superbe !', $crawler->filter('strong')->text());
    }
    
    

}
