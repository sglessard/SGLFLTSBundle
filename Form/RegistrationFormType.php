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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $action = $options['action'] ?: 'registration';

        $builder->add('firstName');
        $builder->add('lastName');
        
        if ($action == 'registration') {
            $builder->add('roles', ChoiceType::class, array(
                'choices' => array('ROLE_ADMIN' => 'ROLE_ADMIN (First user has to be admin)'),
                'multiple' => true,
                'required' => true,
                'expanded' => true,
            ));
        } else {
            $builder->add('roles',ChoiceType::class,array('choices'=>array('ROLE_USER'=>'ROLE_USER','ROLE_BILL'=>'ROLE_BILL','ROLE_ADMIN'=>'ROLE_ADMIN'),'multiple' => true,));
        }
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'sgl_fltsbundle_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}