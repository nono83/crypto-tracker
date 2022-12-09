<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Entity\Investment;
use App\Form\CryptoType;
use App\Repository\CryptoRepository;
use App\Repository\InvestmentRepository;
use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crypto")
 */
class CryptoController extends AbstractController
{
    /**
     * @Route("/", name="app_crypto_index", methods={"GET"})
     */
    public function index(CryptoRepository $cryptoRepository): Response
    {
        return $this->render('crypto/index.html.twig', [
            'cryptos' => $cryptoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_crypto_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CryptoRepository $cryptoRepository, InvestmentRepository $investmentRepository, CallApiService $callApiService): Response
    {
        $crypto = new Crypto();
        $form = $this->createForm(CryptoType::class, $crypto);
        $form->handleRequest($request);

       // dd($callApiService->getCryptosChoicesList());

        //Ajout de la quotation du jour après création de la crypto 
        if ($form->isSubmitted() && $form->isValid()) {
            $cryptoRepository->add($crypto, true);

            // Ajout de la quotation du jour dans le portefeuille après création de la crypto 
            $crypto_id=$crypto->getApiID();
            $quote=$callApiService->getCryptoLastQuote($crypto_id);
            $total=$quote * $crypto->getQuantity();

            $investment=new Investment();
            $investment->setDate(new \DateTime('now'));
            $investment->setCrypto($crypto);
            $investment->setQuote($quote);
            $investment->setTotal($total); 
            $investmentRepository->add($investment, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crypto/new.html.twig', [
            'crypto' => $crypto,
            'form' => $form,
        ]); 
    }

    /**
     * @Route("/show/{id}", name="app_crypto_show", methods={"GET"},requirements={"id":"\d+"})
     */
    public function show(Crypto $crypto): Response
    {
        return $this->render('crypto/show.html.twig', [
            'crypto' => $crypto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_crypto_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Crypto $crypto, CryptoRepository $cryptoRepository): Response
    {
        $form = $this->createForm(CryptoType::class, $crypto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cryptoRepository->add($crypto, true);

            return $this->redirectToRoute('app_crypto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crypto/edit.html.twig', [
            'crypto' => $crypto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/remove/{id}", name="app_crypto_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Crypto $crypto, CryptoRepository $cryptoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$crypto->getId(), $request->request->get('_token'))) {
            $cryptoRepository->remove($crypto, true);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
