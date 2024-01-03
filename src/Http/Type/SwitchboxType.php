<?php

namespace App\Http\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchboxType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder->addViewTransformer( new BooleanToStringTransformer( '1', ['0'] ) );
    }

    public function buildView( FormView $view, FormInterface $form, array $options ) : void
    {
        $view->vars['value'] = '1';
        $view->vars['checked'] = null !== $form->getViewData();
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'compound' => false,
            'label' => false,
        ] );
    }

    public function getBlockPrefix() : string
    {
        return 'switchbox';
    }
}
