<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $nbp = file_get_contents('http://api.nbp.pl/api/exchangerates/tables/c/?format=json');
        $data = json_decode($nbp, TRUE);
        //$exchangeRate = $data["rates"][0]["bid"];
        //$infoToday = 'kurs waluty '.$data['currency'] .' z dnia '.$data["rates"][0]["effectiveDate"].' to '.$exchangeRate;
        
        return $this->render('index/index.html.twig', [
            'infoToday' => $data
        ]);
    }
}