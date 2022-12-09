<?php

namespace App\Form;

use App\Service\CallApiService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Crypto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CryptoType extends AbstractType
{
    private $callApiService;

    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('apiID', ChoiceType::class, [
                'label' => false,
                //'choice_loader' => new CryptosChoiceList(),
                'choices' => $this->callApiService->getCryptosChoicesList()['items'],
                'choice_attr' => $this->callApiService->getCryptosChoicesList()['data-price'],
                /* 'choice_attr' => [
                    'BTCDOWN(BTCDOWN)' => ['data-price' => '0.045456547162325'],
                    'BTCUP(BTCUP)' => ['data-price' => '2.7026056184461'],
                    'BUMO(BU)' => ['data-price' => '0.0012016234952853'],
                ], */
                'placeholder' => 'Sélectionnez une crypto',
                'constraints' => [
                    new NotBlank(['message' => 'Aucune crypto sélectionnée']),
                ],
                'attr' => [
                    'class' => 'input',
                ],
            ])
            
            ->add('quantity',NumberType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Quantité',
                    'class' => 'input',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Aucune quantité saisie']),
                ],
            ])
            
            ->add('price',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix d\'achat',
                    'class' => 'input',
                    'readonly' => true,
                ],
                'disabled' => false,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'AJOUTER',
                'attr' => [
                    'class' => 'btn-submit mb-5',
                ],
            ])

            ->add('name',HiddenType::class)
            ->add('symbol',HiddenType::class)
            ->add('logo',HiddenType::class)
            ->add('evolution',HiddenType::class)

            ->addEventListener(FormEvents::PRE_SUBMIT,function(FormEvent $event){
                //$event->getForm()->add('symbol', TextType::class);
               
                $crypto_id=$event->getData()['apiID'];
                $quantity=$event->getData()['quantity'];
                $price=$event->getData()['price'];
                
                $cryptoInfo=$this->callApiService->getCryptoInfos($crypto_id);
                $name=$cryptoInfo['name'];
                $symbol=$cryptoInfo['symbol'];
                $logo=$cryptoInfo['logo'];
                $evolution='up.png';

                // Avec la méthode d'affectation setData obligé de rééaffecter tous les champs sinon ils ne sont pas pris en compte
                $event->setData(['apiID'=>$crypto_id,'quantity'=>$quantity, 'price' => $price, 'name' =>$name,'symbol' => $symbol, 'logo' => $logo , 'evolution' => $evolution]);
                /* $event->getForm()->add('name',HiddenType::class,['data'=>$name]);
                $event->getForm()->add('symbol',HiddenType::class,['data'=>$symbol]);
                $event->getForm()->add('logo',HiddenType::class,['data'=>$logo]); */

                //dd($event->getData());
            });

           /*  ->addEventListener(FormEvents::POST_SUBMIT,function(FormEvent $event){
                //$event->getForm()->add('symbol', TextType::class);

                $crypto_id=$event->getData()['apiID'];
                $quantity=$event->getData()['quantity'];
                
                $cryptoInfo=$this->callApiService->getCryptoInfos($crypto_id);
                $name=$cryptoInfo['name'];
                $symbol=$cryptoInfo['symbol'];
                $logo=$cryptoInfo['logo'];
                $event->setData(['apiID'=>$crypto_id,'quantity'=>$quantity,'name' =>$name,'symbol' => $symbol, 'logo' => $logo]);
               
            }); */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Crypto::class,
        ]);
    }
}
