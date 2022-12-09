<?php

namespace App\Service;

use App\Entity\Investment;
use App\Repository\CryptoRepository;
use App\Repository\InvestmentRepository;

class InvestmentService
{
    private $CryptoRepository;
    private $investmentRepository;
    private $callApiService;

    public function __construct(CryptoRepository $CryptoRepository, InvestmentRepository $investmentRepository,  CallApiService  $callApiService)
    {
        $this->CryptoRepository=$CryptoRepository;
        $this->investmentRepository=$investmentRepository;
        $this->callApiService=$callApiService;

    }

    /**
     * Mise à jour des quotations du jour pour chaque crypto achetée
     * @param CryptoRepository $CryptoRepository,InvestmentRepository $investmentRepository, CallApiService  $callApiService
     */
    public function updCurrentQuoteByCrypto()
    {
        $cryptos= $this->CryptoRepository->findAll();

        foreach($cryptos as $crypto){
            $crypto_id=$crypto->getApiID();
            $quote=$this->callApiService->getCryptoLastQuote($crypto_id);
            $total=$quote * $crypto->getQuantity();
            $date_current=new \DateTime('now');
            $date_current_format=$date_current->format('Y-m-d');
            
            $investment=$this->investmentRepository->findOneByDateAndCryptoID($crypto,$date_current_format);
            //dd($investment);
            if(is_null($investment)){
                $investment=new Investment();
            }
            $investment->setDate($date_current);
            $investment->setCrypto($crypto);
            $investment->setQuote($quote);
            $investment->setTotal($total); 
            $this->investmentRepository->add($investment, true);  
        }
    }
}