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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identification')
            ->add('name')
            ->add('client',EntityType::class,array(
                'class'         => 'SGLFLTSBundle:Client',
                'choice_label'  => 'name',
                'query_builder' => function (\SGL\FLTSBundle\Entity\ClientRepository $er) {
                    return $er->retrieve(true);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Project'
        ));
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_projecttype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
