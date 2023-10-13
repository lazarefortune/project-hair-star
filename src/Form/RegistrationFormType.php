<?php

namespace App\Form;

use App\Dto\Auth\SubscribeClientDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'email', TextType::class, [
                'label' => 'Email',
            ] )
            ->add( 'fullname', TextType::class, [
                'label' => 'Nom complet',
            ] )
            ->add( 'phone', TextType::class, [
                'label' => 'Numéro de téléphone',
            ] )
            ->add( 'agreeTerms', CheckboxType::class, [
                'constraints' => [
                    new IsTrue( [
                        'message' => 'Vous devez accepter les conditions d\'utilisation',
                    ] ),
                ],
                'label' => false,
            ] )
            ->add( 'plainPassword', PasswordType::class, [
                'attr' => ['autocomplete' => 'new-password'],
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => SubscribeClientDto::class,
        ] );
    }
}
