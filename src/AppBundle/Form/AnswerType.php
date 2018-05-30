<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints as Constraint;

class AnswerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', null, array(
                'label' => 'Description',
                'attr' => array(
                    'placeholder' => 'Propose your answer here',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                ),
            ));
            if ($options['admin'] === true) {
                $builder
                    // ->add('createdAt')
                    ->add('author', null, array(
                        'label' => 'Author',
                        'constraints' => array(
                            new Constraint\NotBlank(),
                        ),
                    ))
                    ->add('isBlocked', null, array(
                        'label' => 'Should this answer be blocked ?'
                    ))
                    ->add('question', null, array(
                        'label' => 'Question associated',
                        'constraints' => array(
                            new Constraint\NotBlank(),
                        ),
                    ))
                    // ->add('voteUsers')
                ;
            }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer',
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
        return 'appbundle_answer';
    }


}
