<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Task;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('deadline',DateTimeType::Class, array(
                'widget' => 'choice',
                'years' => range(2017,2024),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('priority', ChoiceType::class, array(
                'choices'  => array(
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                )))
            ->add('status', ChoiceType::class, array(
                'choices'  => array(
                    'Done' => 'Done',
                    'Not Done' => 'Not Done',
                )))
            ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Task::class,
        ));
    }
}