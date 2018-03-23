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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,array(
                'attr'=>array('size'=>40),
            ))
            ->add('rate')
            ->add('logo',FileType::class,array(
                'image_property' => 'webPath',
                'required' => false
            ))
            ->add('address',TextareaType::class,array(
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

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_clienttype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
