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

class WorkMoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('part',EntityType::class,array(
                'class'         => 'SGLFLTSBundle:Part',
                'choice_label'  => 'fullname',
                'group_by'      => 'clientName',
                'label'         => 'Opened project part',
                'query_builder' => function (\SGL\FLTSBundle\Entity\PartRepository $er) {
                    return $er->retrieveOpened(true);
                },
                'mapped' => false // part isnt a Work field
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_worktype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
