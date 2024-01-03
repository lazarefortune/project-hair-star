<?php

namespace App\Domain\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OffDaysForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder->add( 'offdays', ChoiceType::class, [
            'label' => 'Jours de fermeture',
            'choices' => [
                'Lundi' => '1',
                'Mardi' => '2',
                'Mercredi' => '3',
                'Jeudi' => '4',
                'Vendredi' => '5',
                'Samedi' => '6',
                'Dimanche' => '7',
            ],
            'multiple' => true,
            'expanded' => true,
            'required' => true,
            'label_attr' => [
                'class' => 'label',
            ],
        ] );
    }

}