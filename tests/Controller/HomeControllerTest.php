<?php
namespace App\Tests\Controller;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends  PantherTestCase
{
    //Test d'accès à la page d'ajout d'une nouvelle crypto depuis la page d'accueil
      public function testAccessPageNew()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/home');

        //Pause de 4 s si l'on souhaite voir le navigateur lorsque le  mode HEADLESS=1
       //sleep(4);

        //Click sur le bouton ajout lien vers la page d'ajout d'une nouvelle crypto
        $link = $crawler->filter('#add')->link();
        $crawler = $client->click($link); 

        //Méthode 1 : Test sur le status de la réponse attendu
        //$this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //$this->assertSame(200, $client->getResponse()->getStatusCode());

         //Affiche le contenu de la réponse au format HTML dans le terminal
         //echo $client->getResponse()->getContent();
        
        //Méthode 2 :Test sur la présence d'un sélecteur élément du DOM particulier Sélection d'un élément du DOM particulier soit directement par l'assert soit avec la méthode select du crawler
        $this->assertSelectorTextContains("h1","Ajouter une transaction");
       
    } 

      //Test d'accès à la page de suppression d'une  crypto depuis la page d'accueil
     public function testAccessPageEdit()
     {
         $client = static::createPantherClient();
         $crawler = $client->request('GET', '/home');

         //Click sur le 1e élément de la liste des cryptos pour le sélectionner
         $client->executeScript("document.querySelector('#cryptoTable tr').click()");
        //Pause de 4 s si l'on souhaite voir le navigateur lorsque le  mode HEADLESS=1
        //sleep(4);
         $link = $crawler->filter("#edit")->link();
         $crawler = $client->click($link);  
 
         //Méthode 1 : Test sur le status de la réponse attendu
         //$this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //$this->assertSame(200, $client->getResponse()->getStatusCode());
         
         //Méthode 2 :Test sur la présence d'un sélecteurélément du DOM particulierSélection d'un élément du DOM particulier soit directement par l'assert soit avec la méthode select du crawler
         $this->assertSelectorTextContains("button","VALIDER");
        
     }  

     //Test d'accès à la page du graphique de l’évolution des gains depuis la page d'accueil
     public function testAccessPageChart()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/home');
        
        //Click sur le montant des gains lien vers le graphique
         $link = $crawler->filter('#total')->link();
        $crawler = $client->click($link); 

        //Méthode 1 : Test sur le status de la réponse attendu
        //$this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //$this->assertSame(200, $client->getResponse()->getStatusCode());
        
        //Méthode 2 :Test sur la présence d'un sélecteur élément du DOM particulier.
        $this->assertSelectorTextContains("h1","Vos gains");
       
        //Affiche le contenu de la réponse au format HTML dans le terminal
       // echo $client->getResponse()->getContent();
    }
}