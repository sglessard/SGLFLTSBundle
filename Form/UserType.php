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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled',null,array('required'=>false))
            ->add('roles',ChoiceType::class,array('choices'=>array('ROLE_USER'=>'ROLE_USER','ROLE_BILL'=>'ROLE_BILL','ROLE_ADMIN'=>'ROLE_ADMIN'),'multiple' => true,))
            ->add('first_name')
            ->add('last_name')
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\User',
            'action'     => 'edit',
            'validation_groups' => array('Registration'),
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_usertype';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
