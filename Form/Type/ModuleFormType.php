<?php

namespace Bacon\Bundle\AclBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['search']) {
            $builder
                ->add('name')->setRequired(false)
                ->add('slug')->setRequired(false)
            ;
        } else {
            $builder
                ->add('name')
                ->add('slug')
            ;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'search'     => false,
            'data_class' => 'Bacon\Bundle\AclBundle\Entity\Module'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'bacon_bundle_aclbundle_moduleform';
    }
}
