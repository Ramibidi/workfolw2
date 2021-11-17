<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistartionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, ['required'   => true])
        ->add('email', TextType::class, ['required'   => true])
        ->add('password', PasswordType::class, ['required'   => true])
        ->add('roles', ChoiceType::class, [

            'choices' => [

                'User ' => 'ROLE_USER',

                'Administrator ' => 'ROLE_ADMIN'

            ],

            'expanded' => true,

            'multiple' => true,

            'label' => 'Roles',

            'empty_data' => ['ROLE_USER'],

        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
