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

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,array(
                'attr'=>array('size'=>40),
            ))
            ->add('rate')
            ->add('logo','file',array(
                'image_path' => 'webPath',
                'required' => false
            ))
            ->add('address','textarea',array(
                'attr'=>array('cols'=>40,'rows'=>3),
            ))
            ->add('contact_name',null,array(
                'attr'=>array('size'=>40),
            ))
            ->add('contact_email',null,array(
                'attr'=>array('size'=>40),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_clienttype';
    }
}
