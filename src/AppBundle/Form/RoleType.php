<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Constraint;

class RoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Role name',
                'attr' => array(
                    'placeholder' => 'Must be uppercase, begin with "ROLE_ and 32 characters maximum"',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                    new Constraint\Regex(array(
                        'pattern' => '/^ROLE_[A-Z0-9]+$/',
                        'message' => 'This role name does not match the requirements.'
                    )),
                    new Constraint\Length(array(
                        'max' => 32,
                        'maxMessage' => 'This role name is too long.'
                    )),
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Role',
            'attr' => ['novalidate' => 'novalidate'],
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_role';
    }


}
