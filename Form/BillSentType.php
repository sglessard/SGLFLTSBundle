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
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BillSentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description',null,array(
                'attr'=>array('cols'=>50,'rows'=>3),
                'required'=>false,
            ))
            ->add('sent_at',DateType::class, array(
                'required' => false,
                'widget' => 'single_text'
            ))
            ->add('paid_at',DateType::class, array(
                'required' => false,
                'widget' => 'single_text'
            ))
            ->add('deposited_at',DateType::class, array(
                'required' => false,
                'widget' => 'single_text'
            ))
            ->add('note',null, array(
                'attr'=>array('cols'=>50,'rows'=>3),
                'label'=> 'Note (private)',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Bill'
        ));
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_billsenttype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
