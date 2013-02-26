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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $action = $options['action'];
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled',null,array('required'=>false))
            ->add('roles','choice',array('choices'=>array('ROLE_USER'=>'ROLE_USER','ROLE_BILL'=>'ROLE_BILL','ROLE_ADMIN'=>'ROLE_ADMIN'),'multiple' => true,))
            ->add('first_name')
            ->add('last_name')
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;

        if ($action == 'create') {
            $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SGL\FLTSBundle\Entity\User',
            'action'     => 'edit'
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_usertype';
    }
}
