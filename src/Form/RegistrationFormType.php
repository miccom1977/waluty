<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $currencyData = $this->currencyRepository->findAll(); 
        foreach($currencyData as $value ){
            $builder
            ->add($value->getCurrencyCode(),CheckboxType::class, [
                'label' => $value->getCurrencyName().' ( kurs: '. ( ($value->getCurrencyBid()+$value->getCurrencyAsk())/2).')',
                'mapped' => false,
                'required' => false,
                'attr'     => [
                    'class'=> 'currencyData'
                ] 
            ])
            ->add('min'.$value->getCurrencyCode(),NumberType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'attr'     => [
                    'step' => 0.0001,
                    'value' => $value->getCurrencyBid(),
                ]
            ])
            ->add('max'.$value->getCurrencyCode(),NumberType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'attr'     => [
                    'step' => 0.0001,
                    'value' => $value->getCurrencyAsk(),
                ]
            ]);
        }
        $builder
            ->add('username',TextType::class, [
                'label' => 'Imię'
            ])
            ->add('sername',TextType::class, [
                'label' => 'Nazwisko'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adres email',
                'invalid_message' => 'Taki email mamy już w bazie',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj adres email',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twój email musi posiadać minimum {{ limit }} znaków',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Hasło',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Hasło musi zawierać przynajmniej {{ limit }} znaków',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('phone',TelType::class, [
                'label' => 'Telefon',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj numer telefonu',
                    ]),
                    new Length([
                        'min' => 9,
                        'max' => 9,
                        'minMessage' => 'Numer telefonu musi zawierać przynajmniej {{ limit }} znaków'
                    ]),
                ],
            ])
            ->add('birthday',  DateType::class, [
                'label' => 'Data urodzin',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Akceptuję OWU',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Potwierdź regulamin.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'rejestruj',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);



    }
}
