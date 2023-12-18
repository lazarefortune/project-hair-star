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

        // No label for the field
        $view->vars['input_label'] = ( isset( $options['input_label'] ) && $options['input_label'] ) ? $options['input_label'] : false;

        // Set the label based on the checkbox state
        $view->vars['label_title'] = $view->vars['checked'] ? $options['label_on'] : $options['label_off'];
        $view->vars['label_on'] = $options['label_on'];
        $view->vars['label_off'] = $options['label_off'];
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'compound' => false,
            'label_on' => 'Activé',
            'label_off' => 'Désactivé',
            'input_label' => null,
            'label' => false,
        ] );
    }

    public function getBlockPrefix() : string
    {
        return 'switchbox';
    }
}
