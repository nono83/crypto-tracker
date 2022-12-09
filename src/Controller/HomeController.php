<?php

namespace App\Controller;

use App\Repository\CryptoRepository;
use App\Repository\InvestmentRepository;
use App\Service\CallApiService;
use App\Service\InvestmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   
    /**
     * @Route("/home", name="app_home")
     */
    public function index(CryptoRepository $CryptoRepository,InvestmentRepository $InvestmentRepository, InvestmentService $investmentService, CallApiService $callApiService): Response
    {
        //Mise à jour des quotations du jour pour chaque crypto achetée
        $investmentService->updCurrentQuoteByCrypto();

        // Mise à jour de l'évolution de la quotation du jour par rapport au prix d'achat pour chaque crypto
        // Si quotation > prix d'achat evolution = up sinon down
        $cryptos= $CryptoRepository->findAll();
        $initial_total=0;

        foreach($cryptos as $crypto){
            $crypto_id=$crypto->getApiID();
            $quote=$callApiService->getCryptoLastQuote($crypto_id);
            $evolution=($quote >$crypto->getPrice())?'up.png':'down.png';
            $crypto->setEvolution($evolution);
           
            $CryptoRepository->add($crypto, true);  

            $initial_total+=$crypto->getQuantity() * $crypto->getPrice();
        }

        //Montant total du portefeuille à la date du jour
        $date_current=new \DateTime('now');
        $date_current_format=$date_current->format('Y-m-d');
        $current_total=$InvestmentRepository->findGlobalTotalByDate($date_current_format)['total'];

        $evolution_total=(int)$current_total-(int)$initial_total;
        //Liste de toutes les cryptos achetées
        $cryptos =$CryptoRepository->findAll();

        return $this->render('home/index.html.twig', [
            'cryptos' => $cryptos,
            'total' => sprintf('%s '.$evolution_total,($evolution_total>0)?'+':'-'),
        ]);
    }
}
