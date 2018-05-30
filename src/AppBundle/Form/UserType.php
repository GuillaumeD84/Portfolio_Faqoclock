<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints as Constraint;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    private $admin = false;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->admin = $options['admin'];

        $builder
            ->add('username', null, array(
                'label' => 'Username',
                'attr' => array(
                    'placeholder' => 'Choose a username',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                ),
            ))
            ->add('email', null, array(
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'Your email',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                ),
            ));

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if (!$user || null === $user->getId()) {
                    $form
                    ->add('password', FormType\RepeatedType::class, array(
                        'type' => FormType\PasswordType::class,
                        'invalid_message' => 'Passwords doesn\'t match.',
                        'first_options'  => array(
                            'label' => 'Password',
                            'attr' => array(
                                'placeholder' => 'Choose your password wisely',
                            ),
                        ),
                        'second_options' => array(
                            'label' => 'Repeat your password',
                            'attr' => array(
                                'placeholder' => 'Please repeat your password',
                            ),
                        ),
                        'constraints' => array(
                            new Constraint\NotBlank(),
                        ),
                    ));
                } else {
                    $form
                    ->add('password', FormType\RepeatedType::class, array(
                        'type' => FormType\PasswordType::class,
                        'invalid_message' => 'Passwords doesn\'t match.',
                        'first_options'  => array(
                            'label' => 'Change the current password',
                            'attr' => array(
                                'placeholder' => 'Let this field empty if unchanged',
                            ),
                        ),
                        'second_options' => array(
                            'label' => 'Repeat the new password',
                            'attr' => array(
                                'placeholder' => 'Please repeat the password',
                            ),
                        ),
                    ));
                }

                if ($this->admin === true) {
                    $form
                        ->add('isActive', null, array(
                            'label' => 'Should this user be considered as active ?'
                        ))
                        ->add('role', null, array(
                            'label' => 'Role',
                        ))
                    ;
                }

                // ->add('voteQuestions')
                // ->add('voteAnswers')
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'attr' => ['novalidate' => 'novalidate'],
            'admin' => null,
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
        return 'appbundle_user';
    }


}
