<?php
namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ChartjsControllerTest extends WebTestCase
{

    public function testAccessPageChart()
    {
        $client = static::createClient();
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
?>
