<?php

namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AdminLidFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateofbirth', BirthdayType::class, ['label' => 'Geboortedatum*', 'empty_data' => '', 'required' => false])
            ->add('loginname', TextType::class, ['label' => 'Gebruikersnaam*', 'empty_data' => '', 'required' => false])
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
            ->add('street', TextType::class, ['label' => 'Straat*', 'mapped' => false, 'constraints' => [
                new Assert\NotBlank(['message' => "Vul een straat in"])
            ]])
            ->add('postal_code', TextType::class, ['label' => 'Postcode*', 'mapped' => false, 'constraints' => [
                new Assert\NotBlank(['message' => "Vul een postcode in"])
            ]])
            ->add('city', TextType::class, ['label' => 'Stad*:', 'mapped' => false, 'constraints' => [
                new Assert\NotBlank(['message' => "Vul een stad in"])
            ]])
            ->add('emailaddress', TextType::class, ['label' => 'Email*:', 'empty_data' => '', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class
        ]);
    }
}