<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 07/04/2019
 * Time: 12:10
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('email',EmailType::Class,['error_bubbling' =>true])
            ->add('plainPassword',RepeatedType::class, [
                'type' => PasswordType::class,
                'error_bubbling' =>true,
                'first_options' => ['label' =>'Password'],
                'second_options' => ['label' => 'Repeated password']
            ])
            ->add('termsAgreed', CheckboxType::class,[
                'mapped' => false,
                'constraints' => new IsTrue(),
                'label' => 'Я согласен на обработку персональных данных'
            ])
            ->add('register', SubmitType::class,[
                'label'=>'Зарегистрироваться'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}