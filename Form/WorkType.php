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

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $part = $options['part'];

        $builder
            ->add('task','entity',array(
                'class'         => 'SGLFLTSBundle:Task',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\TaskRepository $er) use ($part) {
                    return $er->retrieve(true)->where('t.part = :id_part')->setParameter('id_part', $part->getId());
                }
            ))
            ->add('user','entity',array(
                'class'         => 'SGLFLTSBundle:User',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\UserRepository $er) {
                    return $er->retrieve(true);
                }
            ))
            ->add('rate','entity',array(
                'class'         => 'SGLFLTSBundle:Rate',
                'choice_label'  => 'name',
                'query_builder' => function (\SGL\FLTSBundle\Entity\RateRepository $er) {
                    return $er->retrieve(true);
                }
            ))
            ->add('name',null,array(
                'attr' => array('class' => 'longtext'),
            ))
            ->add('description',null,array(
                'attr'=>array('rows' => 3, 'cols'=>60, 'class'=>'longtext')
            ))
            ->add('worked_at','genemu_jquerydate',array(
                'required' => true,
                'widget' => 'single_text'
            ))
            ->add('started_at', 'time', array(
                'input'       => 'datetime',
                'widget'      => 'choice',
                'minutes'     => array(0,6,12,18,24,30,36,42,48,54),
                'with_seconds'=> false,
            ))
            ->add('ended_at', 'time', array(
                'input'       => 'datetime',
                'widget'      => 'choice',
                'minutes'     => array(0,6,12,18,24,30,36,42,48,54),
                'with_seconds'=> false,
            ))
            ->add('revision')
            ->add('do_not_bill')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'part'      => null,
            'data_class' => 'SGL\FLTSBundle\Entity\Work'
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_worktype';
    }
}
