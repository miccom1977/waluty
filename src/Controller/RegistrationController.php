<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Currency;
use App\Entity\UserCurrency;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // validate phone number
            $firstNumber = str_split($form->get('phone')->getData());
            if($firstNumber[0] == 0 ){
                $this->addFlash('error', 'Pierwszy znak to liczba 0, numer telefonu  nieprawdziwy');
            }else{
                //echo 'pierwszy znak nie jest zerem';
                if( is_numeric($form->get('phone')->getData())){
                    //echo 'walidacja poprawna, numer telefonu to 9 liczb i pierwsza nie jest zerem';
                    $user->setPhone($form->get('phone')->getData());
                    // sprawdzamy, czy user jest pełnoletni
                    
                    $birthDateformat = date_format($form->get('birthday')->getData(), 'Y-m-d');
                    $birthDateTimestamp = strtotime($birthDateformat);
                    $userYears = round( ( time() - $birthDateTimestamp)/(365*24*3600) );
                    
                    if( $userYears >= 18){
                        //echo 'user jest matur;), ma '.$userYears.' lata';
                        // zapisujemy do bazy birthday jako timpestamp
                        $user->setBirthday($birthDateTimestamp);
                        $user->setMailingActivate(0);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                        // dodajemy zaznaczone waluty do śledzenia
                        $currency = $this->getDoctrine()->getRepository(Currency::class);
                        $currencyData = $currency->findAll();
                        $currencyInfo = '';
                        foreach( $currencyData as $sCur ){
                            if (true === $form->get($sCur->getCurrencyCode())->getData()) {
                                $userCurrency =  new UserCurrency();
                                $userCurrency->setCurrencyId($sCur->getId());
                                $userCurrency->setCurrencyMin( $form->get('min'.$sCur->getCurrencyCode())->getData() );
                                $userCurrency->setCurrencyMax( $form->get('max'.$sCur->getCurrencyCode())->getData() );
                                $userCurrency->setUserId( $user->getId() );
                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->persist($userCurrency);
                                $entityManager->flush();
                                $currencyInfo .= 'Zaznaczono walutę '. $sCur->getCurrencyName() .' do śledzenia.<br>';
                            }
                        }

                        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                            (new TemplatedEmail())
                                ->from(new Address('biuro@web-kod.pl', 'Pracownik rejestracji'))
                                ->to($user->getEmail())
                                ->subject('Potwierdź rejestrację w systemie Waluty')
                                ->htmlTemplate('registration/confirmation_email.html.twig')
                                ->context([
                                    'currencyInfo' => $currencyInfo
                                ])
                        );
                        return $this->redirectToRoute('index')->addFlash('info', 'Poprawnie zarejestrowano');

                    }else{
                        //echo 'user nie jest pełnoletni, ma tylko '. $userYears .' lat';
                        $this->addFlash('error', 'Nie jesteś pełnoletni, masz dopiero '. $userYears .' lat');
                    }
                }else{
                    $this->addFlash('error', 'Podaj poprawny numer telefonu');
                }
            }
            
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
