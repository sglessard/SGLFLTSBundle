<?php

namespace SGL\FLTSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('rate')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\Rate'
        ));
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_ratetype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
