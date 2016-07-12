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

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Show closed part if it is the selected part
        $selected_part_id = isset($options['selected_part_id']) ? $options['selected_part_id'] : null;

        $builder
            ->add('part','entity',array(
                'class'         => 'SGLFLTSBundle:Part',
                'choice_label'  => 'fullname',
                'group_by'      => 'clientName',
                'label'         => 'Opened project part',
                'placeholder'   => 'Select one',
                'query_builder' => function (\SGL\FLTSBundle\Entity\PartRepository $er) use ($selected_part_id) {
                    return $er->retrieveOpened(true, $selected_part_id);
                }))
            ->add('number')
            ->add('name')
            ->add('description',null,array(
                'attr'=>array('cols'=>50,'rows'=>3),
                'required'=>false,
            ))
            ->add('ordered_at','genemu_jquerydate', array(
                'widget' => 'single_text'
            ))
            ->add('billed_at','genemu_jquerydate', array(
                'widget' => 'single_text'
            ))
            ->add('taxable')
            ->add('rate','entity',array(
                'class'         => 'SGLFLTSBundle:Rate',
                'choice_label'  => 'name',
                'query_builder' => function (\SGL\FLTSBundle\Entity\RateRepository $er) {
                    return $er->retrieve(true);
                }
            ))
            ->add('extra_hours')
            ->add('extra_fees')
        ;

        if (!$options['new_entity']) {
            $builder
                ->add('body_content','genemu_tinymce',array(
                    'attr'=>array('style'=>'width: 680px; height:600px;'),
                    'required'=>false,
                ))
                ->add('sent_at','genemu_jquerydate', array(
                    'required' => false,
                    'widget' => 'single_text'
                 ))
                ->add('paid_at','genemu_jquerydate', array(
                    'required' => false,
                    'widget' => 'single_text'
                 ))
                ->add('deposited_at','genemu_jquerydate', array(
                    'required' => false,
                    'widget' => 'single_text'
                 ))
                ->add('note',null, array(
                    'attr'=>array('cols'=>50,'rows'=>3),
                    'required' => false
                ))
            ;
        }

        if ($options['use_gst']) {
            $builder->add('gst');
        }
        if ($options['use_pst']) {
            $builder->add('pst');
        }
        if ($options['use_hst']) {
            $builder->add('hst');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Bill',
            'use_gst'    => true,
            'use_pst'    => true,
            'use_hst'    => false,
            'new_entity' => false,
            'selected_part_id' => null
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_billtype';
    }
}
