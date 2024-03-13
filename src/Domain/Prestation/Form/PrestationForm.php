<?php

namespace App\Domain\Prestation\Form;

use App\Domain\Category\Entity\Category;
use App\Domain\Prestation\Entity\Prestation;
use App\Domain\Tag\Entity\Tag;
use App\Helper\CentToEuroTransformer;
use App\Helper\MinutesToTimeHelper;
use App\Http\Type\PriceType;
use App\Http\Type\SwitchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationForm extends AbstractType
{

    public function __construct(
        private readonly MinutesToTimeHelper $minutesToTimeHelper,
    )
    {
    }

    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'name', TextType::class, [
                'label' => 'Nom du service',
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'description', TextareaType::class, [
                'label' => 'Description du service',
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'required' => false,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'price', PriceType::class, [
                'label' => 'Prix du service',
                'currency' => 'EUR',
                'required' => true,
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'duration', TimeType::class, [
                'label' => 'Durée du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-input',
                ],
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startDate', DateType::class, [
                'label' => 'Date de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-input',
                ],
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endDate', DateType::class, [
                'label' => 'Date de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-input',
                ],
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startTime', TimeType::class, [
                'label' => 'Heure de début du service',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endTime', TimeType::class, [
                'label' => 'Heure de fin du service',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'categoryPrestation', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégories',
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'avalaibleSpacePerPrestation', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'bufferTime', ChoiceType::class, [
                'label' => 'Temps de pause',
                'choices' => [
                    '0 minutes' => 0,
                    '5 minutes' => 5,
                    '10 minutes' => 10,
                    '15 minutes' => 15,
                    '20 minutes' => 20,
                    '25 minutes' => 25,
                    '30 minutes' => 30,
                    '35 minutes' => 35,
                    '40 minutes' => 40,
                    '45 minutes' => 45,
                    '50 minutes' => 50,
                    '55 minutes' => 55,
                    '60 minutes' => 60,
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'label' => 'Tags',
                'attr' => [
                    'class' => 'form-input-md select2-tags',
//                    'data-tags' => 'true'
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
                'multiple' => true,
                'required' => false,
            ] )
            ->add( 'isActive', SwitchType::class, [
                'label' => 'Activer le service ?',
                'label_attr' => [
                    'class' => 'label',
                ],
                'required' => true,
            ] )
            ->add( 'considerChildrenForPrice', SwitchType::class, [
                'label' => 'Prendre en compte les enfants pour le prix ?',
                'label_attr' => [
                    'class' => 'label children-price',
                ],
                'required' => true,
            ] )
            ->add( 'childrenAgeRange', ChoiceType::class, [
                'label' => 'Tranche d\'âge des enfants',
                'choices' => [
                    '0-3 ans' => '3',
                    '0-4 ans' => '4',
                    '0-5 ans' => '5',
                    '0-6 ans' => '6',
                    '0-7 ans' => '7',
                    '0-8 ans' => '8',
                    '0-9 ans' => '9',
                    '0-10 ans' => '10',
                    '0-11 ans' => '11',
                    '0-12 ans' => '12',
                    '0-13 ans' => '13',
                    '0-14 ans' => '14',
                    '0-15 ans' => '15',
                    '0-16 ans' => '16',
                    '0-17 ans' => '17',
                    '0-18 ans' => '18',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'childrenPricePercentage', IntegerType::class, [
                'label' => '% de réduction du prix pour les enfants',
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'required' => false,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] );


        $builder->get( 'bufferTime' )->addModelTransformer( $this->minutesToTimeHelper );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Prestation::class,
        ] );
    }
}
