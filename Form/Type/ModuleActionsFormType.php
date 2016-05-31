<?php

namespace Bacon\Bundle\AclBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleActionsFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['search']) {
            $builder
                ->add('module', null, [
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'select2'
                    ]
                ])->setRequired(false)
                ->add('name')->setRequired(false)
                ->add('identifier')->setRequired(false)
            ;
        } else {
            $builder
                ->add('module', null, [
                    'placeholder' => 'Selecione um modulo',
                    'attr' => [
                        'class' => 'select2'
                    ]
                ])
                ->add('name')
                ->add('identifier', null, [
                    'attr' => [
                        'placeholder' => 'Este campo deve conter as actions do modulo exemplo: INDEX, NEW, EDIT, SHOW, DELETE'
                    ]
                ])
            ;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'search'     => false
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'baconacl_moduleactionsform';
    }
}
