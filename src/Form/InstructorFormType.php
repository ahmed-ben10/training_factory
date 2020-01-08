<?php

namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class InstructorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Voornaam*', 'empty_data' => '', 'required' => false])
            ->add('preprovision', TextType::class, ['label' => 'Tussenvoegsel'])
            ->add('lastname', TextType::class, ['label' => 'Achternaam*', 'empty_data' => '', 'required' => false])
            ->add('loginname',TextType::class,['label'=>'Gebruikersnaam*','empty_data'=>'','required'=>false])
            ->add('password',RepeatedType::class,[
                'empty_data'=>'',
                'type'=>PasswordType::class,
                'invalid_message'=>'De wachtwoord velden moeten matchen',
                'first_options'=> ['label' => 'Wachtwoord'],
                'second_options'=> ['label' => 'Herhaling wachtwoord']
            ])

            ->add('dateofbirth', BirthdayType::class, ['label' => 'Geboortedatum*', 'empty_data' => '', 'required' => false])
            ->add('gender', ChoiceType::class, [
                'label' => 'Man/Vrouw',
                'choices' => [
                    'Man' => 'Man',
                    'Vrouw' => 'Vrouw'
                ],
                'expanded' => true,
                'empty_data' => '',
                'multiple' => false,
            ])
            ->add('salary',NumberType::class, [
                'label' => 'Salaris*',
                'empty_data' => '',
                'mapped'=>false,
                'required' => false
            ])
            ->add('emailaddress', TextType::class, ['label' => 'Email*:', 'empty_data' => '', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class
        ]);
    }
}