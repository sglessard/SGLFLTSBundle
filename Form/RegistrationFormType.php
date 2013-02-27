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

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('roles', 'choice', array(
            'choices' => array('ROLE_ADMIN' => 'ROLE_ADMIN (First user has to be admin)'),
            'multiple' => true,
            'required' => true,
            'expanded' => true,
        ));
    }

    public function getName()
    {
        return 'sgl_fltsbundle_user_registration';
    }
}