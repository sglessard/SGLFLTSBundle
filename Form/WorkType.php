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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $part = $options['part'];

        $builder
            ->add('task',EntityType::class,array(
                'class'         => 'SGLFLTSBundle:Task',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\TaskRepository $er) use ($part) {
                    return $er->retrieve(true)->where('t.part = :id_part')->setParameter('id_part', $part->getId());
                }
            ))
            ->add('user',EntityType::class,array(
                'class'         => 'SGLFLTSBundle:User',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\UserRepository $er) {
                    return $er->retrieve(true);
                }
            ))
            ->add('rate',EntityType::class,array(
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
            ->add('worked_at',DateType::class,array(
                'required' => true,
                'widget' => 'single_text'
            ))
            ->add('started_at', TimeType::class, array(
                'input'       => 'datetime',
                'widget'      => 'choice',
                'minutes'     => array(0,6,12,18,24,30,36,42,48,54),
                'with_seconds'=> false,
            ))
            ->add('ended_at', TimeType::class, array(
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

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_worktype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
