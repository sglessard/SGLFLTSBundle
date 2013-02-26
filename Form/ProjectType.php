<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identification')
            ->add('name')
            ->add('client','entity',array(
                'class'         => 'SGLFLTSBundle:Client',
                'property'      => 'name',
                'query_builder' => function (\SGL\FLTSBundle\Entity\ClientRepository $er) {
                    return $er->retrieve(true);
                }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_projecttype';
    }
}
