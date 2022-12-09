<?php

namespace App\Controller;

use App\Entity\Investment;
use App\Repository\CryptoRepository;
use App\Repository\InvestmentRepository;
use App\Service\CallApiService;
use App\Service\InvestmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartjsController extends AbstractController
{
    /**
     * @Route("/chartjs", name="app_chartjs")
     */
    public function index(InvestmentRepository $investmentRepository, InvestmentService $investmentService,  ChartBuilderInterface $chartBuilder,CallApiService $callApiService,EntityManagerInterface $em ): Response    
    {
       //Mise à jour des quotations du jour pour chaque crypto achetée
       $investmentService->updCurrentQuoteByCrypto();
        
        //COnstruction et alimentation du graph
        $investmentResults=$investmentRepository->findChartTotal();

        $labels=[];
        $datas=[];

        foreach($investmentResults as $investmentResult){
            $labels[]=$investmentResult['date']->format('d/m/Y');
            $datas[]=$investmentResult['total'];
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    //Couleur et libelé du label
                    //'label' => 'Valeur du portefeuille',
                    'backgroundColor' => 'rgb(31, 195, 108)',
                    'borderColor' => 'rgb(31, 195, 108)',
                    'data' => $datas,
                ],
            ],
        ]);

         $chart->setOptions([
            'responsive' => true,
            //Cache le label
            'plugins' => [
                'legend' => [
                    'display' => false,
                ], 
            ],
            'scales' => [
                'x' => [
                    //Valeurs de x
                    'ticks' => [
                        'color' => 'rgb(239, 239, 239)',
                    ],
                    //Titre de x
                    'title' => [
                        'display'  => true,
                        'text'     => 'Date',
                        'color' => 'rgb(239, 239, 239)',
                        'font' => [
                            'size' =>  20,
                        ]
                    ],
                ], 
                'y' => [
                    //Valeurs de y
                    'ticks' => [
                        'color' => 'rgb(239, 239, 239)',
                    ],
                    //Titre de y
                    'title' => [
                        'display'  => true,
                        'text'     => 'Valeur',
                        'color' => 'rgb(239, 239, 239)',
                        'font' => [
                            'size' =>  20,
                        ]
                    ],
                ], 
            ],
        ]); 

        return $this->render('chartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
    }
}
