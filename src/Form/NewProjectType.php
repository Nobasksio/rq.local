<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project_name')
            ->add('kitchen_type', TextType::class)
            ->add('type_menu',ChoiceType::class,[
                'choices'  => [
                    'Новое меню на основе старого' => 1,
                    'Совсем новое меню' => 2,
                    'Сезонное предложение' => 3,
                    'Другое'=>0
                ],
                'label' =>'Какое меню будем делать?'
            ])
            ->add('type_category',ChoiceType::class,[
                'choices'  => [
                    'Кухня' => 1,
                    'Бар' => 2,
                    'Кухня и бар' => 3,
                ],
                'label' =>'Что входит'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
