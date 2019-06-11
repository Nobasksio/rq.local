<?php

namespace App\Form;

use App\Entity\Analitics;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Analitics2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('date_start',TextType::class)
            ->add('date_finish',TextType::class)

        ;
        $builder->get('date_start')
            ->addModelTransformer(new CallbackTransformer(
                function ($date_object) {
                    // преобразовать массив в строку
                    return $date_object->format('d.m.Y');
                },
                function ($date_str) {
                    // преобразовать строку обратно в массив
                    return \DateTime::createFromFormat('d.m.Y',$date_str);
                }
            ))
        ;
        $builder->get('date_finish')
            ->addModelTransformer(new CallbackTransformer(
                function ($date_object) {
                    // преобразовать массив в строку
                    return $date_object->format('d.m.Y');
                },
                function ($date_str) {
                    // преобразовать строку обратно в массив
                    return \DateTime::createFromFormat('d.m.Y',$date_str);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Analitics::class,
        ]);
    }
}
