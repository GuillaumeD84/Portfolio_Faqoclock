<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints as Constraint;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'Question title',
                'attr' => array(
                    'placeholder' => 'Question',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                    new Constraint\Length(array(
                        'max' => 255,
                        'maxMessage' => 'This title name is too long.'
                    )),
                ),
            ))
            ->add('body', null, array(
                'label' => 'Description',
                'attr' => array(
                    'placeholder' => 'Describe your problem here',
                ),
                'constraints' => array(
                    new Constraint\NotBlank(),
                ),
            ));
            if ($options['admin'] === true) {
                $builder
                    ->add('author', null, array(
                        'label' => 'Author',
                        'constraints' => array(
                            new Constraint\NotBlank(),
                        ),
                    ))
                ;
            }
            $builder
                ->add('tags', null, array(
                    'label' => 'Tags associated',
                    'expanded' => true,
                    'multiple' => true,
                ))
            ;
            // ->add('createdAt')
            // ->add('validatedAnswer')
            // ->add('voteUsers')
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Question',
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
        return 'appbundle_question';
    }


}
