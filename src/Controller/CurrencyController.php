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
            $singleCurrency->setCurrencyName( $value['currency'] );
            $singleCurrency->setCurrencyCode( $value['code'] );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($singleCurrency);
            $entityManager->flush();
            echo $value['currency'].' zaktualizowano<br>';
<<<<<<< HEAD
        }
        exit();
=======
            //print_r($data['0']['rates']);
        }
        
       //var_dump($data['0']['rates']);
        //echo '</pre>';
        exit();
        /*
        return $this->render('currency_data/index.html.twig', [
            'controller_name' => 'CurrencyController',
        ]);
        */
>>>>>>> ab1f31352488eed46775edfd292e809175e88b9c
    }
}
