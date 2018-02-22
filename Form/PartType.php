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

class PartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $client = $options['client'];

        $builder
            ->add('project',EntityType::class,array(
                'class'         => 'SGLFLTSBundle:Project',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\ProjectRepository $er) use ($client) {
                    return $er->retrieve(true)->where('p.client = :id_client')->setParameter('id_client', $client->getId());
                }
            ))
            ->add('po')
            ->add('identification',null,array('required' => false))
            ->add('name')
            ->add('started_at','genemu_jquerydate',array(
                'widget' => 'single_text'
            ))
            ->add('estimated_hours')
            ->add('closed_at','genemu_jquerydate',array(
                'required' => false,
                'widget' => 'single_text'
            ))
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'client'     => null,
            'data_class' => 'SGL\FLTSBundle\Entity\Part'
        ));
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_parttype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
