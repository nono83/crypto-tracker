<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class CallApiService 
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client=$client;
    }


    private function getApi(string $url,array $params)
    {
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accepts' => 'application/json',
                    'X-CMC_PRO_API_KEY' => 'e9aaa74f-8def-4ade-8584-6bd6a48dcf0e',
                ],
                'query' => $params,
            ]
        );

        return $response->toArray();
    }

    /**
     * Retourne les infos principales de la crypto monnaie en paramétre
     * @param { int } $id id de la crypto monnaie
     * return array avec le nom, symbol et icone de la crypto monnaie
     */
    public function getCryptoInfos(int $id):array
    {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info';
        $params = [
          'id' => $id
        ]; 
        $cryptoInfos=[];
        $crypto=$this->getApi($url,$params);
        $cryptoInfos['name']=$crypto['data'][$id]['name'];
        $cryptoInfos['symbol']=$crypto['data'][$id]['symbol'];
        $cryptoInfos['logo']=$crypto['data'][$id]['logo'];

        return $cryptoInfos;
    }

    /**
     * Récupére la dernière quotation de la crypto monnaie en paramétre
     * @param { int } $id id de la crypto monnaie
     */
    public function getCryptoLastQuote(int $id):float
    {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';
        $params = [
          'id' => $id
        ]; 

        $crypto=$this->getApi($url,$params);

        return $crypto['data'][$id]['quote']['USD']['price'];
    }

    /**
     * Récupére la dernière quotation de la crypto monnaie en paramétre
     * Pas disponible dans le plan gratuit de CoinMarketCap
     * @param { int } $id id de la crypto monnaie
     */
    public function getCryptoUpDateQuotes(int $id):float
    {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/historical';
        $params = [
            'id' => '1',
            'interval' => '24h',
            'time_start' =>'2022-01-01',
            'time_end' => '2022-01-15'
        ]; 

        $crypto=$this->getApi($url,$params);

        return $crypto['data'][$id]['quote']['USD']['price'];
    }

    /**
     * Renvoie la liste de toutes les cryptos active de type coins dont le prix min est  >0.3 USD 
     * @param { int } $id id de la crypto monnaie
     * return array avec le nom, symbol et prix d'achat 
     * Prix d'achat est affecté à l'attribut data-price de chaque option du select pour l'intégrer directement dans la liste et faire l'économie d'un appel à l'API
     */
    public function getCryptosChoicesList():array
    {
        $url='https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $params=[
            'start' => 1,
            'price_min' => 0.3,
            'limit' => 200,
            'sort' => 'name',
            'cryptocurrency_type' => 'coins'
        ];
        $cryptos=$this->getApi($url,$params);
        //$cryptos=json_decode($response,true);
        $cryptoChoicesList=[];
        $dataPrice=[];
        foreach($cryptos['data'] as $crypto){
            $dataPrice['data-price']=$crypto['quote']['USD']['price'];
            $cryptoChoicesList['items'][$crypto['name'].'('.$crypto['symbol'].')'] = $crypto['id'];
            // $cryptoChoicesList['data-price'][$crypto['name'].'('.$crypto['symbol'].')'] = $crypto['quote']['USD']['price'];
             $cryptoChoicesList['data-price'][$crypto['name'].'('.$crypto['symbol'].')']= $dataPrice; 
        }
        //return $cryptos['data'];
        return  $cryptoChoicesList;
        
    }

    /**
     * Récupére la dernière quotation de la crypto monnaie en paramétre
     * Pas disponible dans le plan gratuit de CoinMarketCap
     * @param { int } $id id de la crypto monnaie
     */
/*     public function updateToDayQuotes(int $id):float
    {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/historical';
        $params = [
            'id' => '1',
            'interval' => '24h',
            'time_start' =>'2022-01-01',
            'time_end' => '2022-01-15'
        ]; 

        $crypto=$this->getApi($url,$params);

        return $crypto['data'][$id]['quote']['USD']['price'];
    } */
}