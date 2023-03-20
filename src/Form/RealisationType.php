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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isPublic', ChoiceType::class, [
                'label' => 'Souhaitez-vous rendre cette réalisation publique ?',
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'attr' => [
                    'class' => 'form-control-radio',
                ],
                'expanded' => true,
                'data' => 1,
            ])
            ->add('tarif', NumberType::class, [
                'attr' => [
                    'class' => 'form-control form-control-tarif',
                ],
                'scale' => 2,
            ])
            ->add('isTarifPublic', ChoiceType::class, [
                'label' => 'Souhaitez-vous rendre le tarif public ?',
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'attr' => [
                    'class' => 'form-control-radio',
                ],
                'expanded' => true,
                'data' => 1,
            ])
            ->add('dateRealisation', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Date de la réalisation',
            ])
            ->add('heureDebut', TimeType::class, [
                'label' => 'Heure de début',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('heureFin', TimeType::class, [
                'label' => 'Heure de fin',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('images', FileType::class, [
                'label' => 'Ajoutez des images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'inputfile inputfile-6',
                    'placeholder' => 'Images',
                    'data-multiple-caption' => '{count} fichiers sélectionnés',
                ],
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}
