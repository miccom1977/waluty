<?php

namespace App\Controller;
use App\Entity\Currency;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
            $singleCurrency->setCurrencyCode( strtolower($value['code']) );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($singleCurrency);
            $entityManager->flush();
            echo $value['currency'].' zaktualizowano<br>';
        }
        exit();
    }

    /**
     * @Route("/currencyCheck", name="currency_check")
     */
    public function checkCurrency(MailerInterface $mailer): Response
    {
        // wyszukuję userów których waluty się zmieniły

        $em = $this->getDoctrine()->getManager();
        $allUsers = $em->getRepository(User::class)->findAll();
        foreach( $allUsers as $singleUsers) {
            $data = '
                SELECT `cu`.`currency_name`,`uc`.`currency_min`, `cu`.`currency_bid`, `uc`.`currency_max`, `cu`.`currency_ask` FROM `user_currency` AS `uc` 
                LEFT JOIN `currency` AS `cu` ON ( `uc`.`currency_id`=`cu`.`id`)
                WHERE ( `uc`.`currency_min`> `cu`.`currency_bid` OR `uc`.`currency_max` > `cu`.`currency_Ask` ) AND `uc`.`user_id`= '. $singleUsers->getId() .'
            ';
            $statement = $em->getConnection()->prepare($data);
            $statement->execute();
            $result = $statement->fetchAll();
            $info = 'System wymiany walut.<br>
            Poniżej przesyłamy zmiany w śledzonych walutach: ';
            foreach( $result as $singleResult ){
                if( $singleResult['currency_min'] > $singleResult['currency_bid'] ){
                    $info .= 'Waluta '. $singleResult['currency_name'] .' staniała!<br>';
                }
                if( $singleResult['currency_max'] > $singleResult['currency_ask'] ){
                    $info .= 'Waluta '. $singleResult['currency_name'] .' zdrożała!<br>';
                }
            }
            echo 'Wysłam email do usera '.$singleUsers->getEmail().' z treścią:'.$info;

            $email = (new Email())
            ->from('biuro@web-kod.pl')
            ->to($singleUsers->getEmail())
            ->subject('Ceny walut uległy zmianie!')
            ->text($info)
            ->html('<p>'.$info.'</p>');
            $mailer->send($email);
            exit();
        }
    }








}
