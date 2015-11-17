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

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];

        $builder
            ->add('name')
            ->add('identification')
            ->add('estimated_hours')
            ->add('rank')
            ->add('part','entity',array(
                'label'         => 'Project part',
                'class'         => 'SGLFLTSBundle:Part',
                'choice_label'  => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\PartRepository $er) use ($project) {
                    return $er->retrieveWithProjectClient(true)->andWhere('p.project = :id_project')->setParameter('id_project', $project->getId());
                }))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'project'   => null,
            'data_class' => 'SGL\FLTSBundle\Entity\Task',
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_tasktype';
    }
}
