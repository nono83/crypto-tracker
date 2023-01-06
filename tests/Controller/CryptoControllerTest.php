<?php
namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CryptoControllerTest extends WebTestCase
{
    //fonction de test pour l’ajout d’une nouvelle crypto dans le portefeuille
     public function testAddNewCrypto()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto/new');

        $form = $crawler->selectButton("AJOUTER")->form(
/*             'apiID' => '',
            'quantity' => '',
            'price' => '',
            'name' => '',
            'logo' => '',
            'symbol' => '',
            'evolution' => '', */
        );
        $form["crypto[apiID]"] = "3";
        $form["crypto[quantity]"] = "3";
        $form["crypto[price]"] = "16183.04";
        $form["crypto[logo]"] = "https://s2.coinmarketcap.com/static/img/coins/64x64/1.png";
        $form["crypto[symbol]"] = "BTC";
        $form["crypto[evolution]"] = "up.png";

        $client->submit($form);
        //On s'attend à une redirection après validation
        $this->assertResponseRedirects('');
        //Pour suivre la redirection et voir la page vers laquelle on est redirigé
        $crawler = $client->followRedirect();
        //On vérifie que l'on a bien un sélecteur avec l'id 'logo' sur la page vers laquelle on est redirigé
        $this->assertSame(1, $crawler->filter('#logo')->count());
    } 

    //fonction de test pour la suppression d’une  crypto du portefeuille
    public function testDeleteCrypto()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/crypto/show/23');

        $form = $crawler->selectButton("VALIDER")->form();

        $client->submit($form);
        //On s'attend à une redirection après validation
        $this->assertResponseRedirects('');
        //Pour suivre la redirection et voir la page vers laquelle on est redirigé
        $crawler = $client->followRedirect();
        //On vérifie que l'on a bien un sélecteur avec l'id 'logo' sur la page vers laquelle on est redirigé
        $this->assertSame(1, $crawler->filter('#logo')->count());
    } 
}
?>
