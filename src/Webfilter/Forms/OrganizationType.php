<?php

namespace Webfilter\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Webfilter\Forms\ListTransformer;

class OrganizationType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'webfilter';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('org_id', 'text', array(
                'label' => 'Organization ID',
                'property_path' => 'id',
            ))
            ->add(
                $builder->create('whitelist', 'textarea', array(
                    'label' => 'White List',
                ))
                ->addModelTransformer(new ListTransformer())
            )
            ->add(
                $builder->create(
                    'blacklist', 'textarea', array(
                        'label' => 'Black List'
                ))
                ->addModelTransformer(new ListTransformer())
            )
            ->add('blockedcategories', 'choice', array(
                'multiple' => true,
                'expanded' => true,
                'choices' => array(
                    '0' => 'Alcohol',
                    '1' => 'Art',
                    '2' => 'Blogs',
                    '3' => 'Botnets'
                ),
                'label' => 'Blocked Categories',
                'property_path' => 'categories',
            ))
            ->add('blockedapps', 'choice', array(
                'choices' => array('Dropbox', 'Facebook'),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Blocked Web 2.0 Apps',
                'property_path' => 'apps',
            ))
            ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webfilter\\Document\\Organization',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'id',
        ));
    }
}
