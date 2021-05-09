<?php

namespace App\Controller;
use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/currencyUpdate", name="currency_update")
     */
    public function index(): Response
    {
        
        $nbp = file_get_contents('http://api.nbp.pl/api/exchangerates/tables/c/?format=json');
        $data = json_decode($nbp, TRUE);
        foreach( $data['0']['rates'] as $value) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $singleCurrency = $entityManager->getRepository(Currency::class)->findOneBy(['currencyName' => $value['currency'] ]);
            if (!$singleCurrency) {
                $singleCurrency = new Currency();
            }
            $singleCurrency->setCurrencyBid( $value['bid'] );
            $singleCurrency->setCurrencyAsk( $value['ask'] );
            $singleCurrency->setCurrencyName( strtolower($value['currency']) );
            $singleCurrency->setCurrencyCode( $value['code'] );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($singleCurrency);
            $entityManager->flush();
            echo $value['currency'].' zaktualizowano<br>';
        }
        exit();
    }
}
