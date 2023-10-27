<?php

namespace App\Form;

use App\Dto\ContactDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'name', TextType::class,
                [
                    'label' => 'Nom',
                    'attr' => [
                        'placeholder' => 'John Doe',
                    ],
                ] )
            ->add( 'email', EmailType::class,
                [
                    'label' => 'Email',
                    'attr' => [
                        'placeholder' => 'johndoe@gmail.com'
                    ],
                ] )
            ->add( 'subject', TextType::class,
                [
                    'label' => 'C\'est à propos de ?',
                    'attr' => [
                        'placeholder' => 'Ex: Problème de connexion',
                    ],
                ] )
            ->add( 'message', TextareaType::class,
                [
                    'label' => 'Expliquez-nous en détail',
                    'attr' => [
                        'placeholder' => 'Votre message',
                    ],
                ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => ContactDto::class,
        ] );
    }
}
