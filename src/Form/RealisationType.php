<?php

namespace App\Form;

use App\Entity\Realisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RealisationType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'isPublic', ChoiceType::class, [
                'label' => 'Visible par :',
                'choices' => [
                    'Tout le monde' => 1,
                    'Moi uniquement' => 0,
                ],
                'attr' => [
                    'class' => 'form-control-radio',
                ],
//                'expanded' => true,
            ] )
            ->add( 'tarif', NumberType::class, [
                'attr' => [
                    'class' => 'form-control form-control-tarif',
                    'value' => '20.00',
                ],
                'scale' => 2,
            ] )
            ->add( 'isTarifPublic', ChoiceType::class, [
                'label' => 'Souhaites-tu afficher le tarif ?',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                'attr' => [
                    'class' => 'form-control-radio',
                ],
            ] )
            ->add( 'dateRealisation', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Date de la réalisation',
            ] )
            ->add( 'duration', TimeType::class, [
                'label' => 'Durée de la réalisation',
                'required' => false,
                'widget' => 'single_text',
            ] )
            ->add( 'images', FileType::class, [
                'label' => 'Ajoutez des images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'inputfile inputfile-6',
                    'placeholder' => 'Images',
                    'data-multiple-caption' => '{count} fichiers sélectionnés',
                ],
            ] );
    }


    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Realisation::class,
        ] );
    }
}
