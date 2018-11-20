<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Todo;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('deadline', DatetimeType::class, array('pickerOptions' =>
                array('format' => 'dd.mm.yyyy hh:ii')))
            ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Todo::class,
        ));

    }
}