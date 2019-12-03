<?php


namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('firstname',TextType::class,[ 'label'=>'Voornaam*'])
            ->add('preprovision',TextType::class,['label'=>'Tussenvoegsel'])
            ->add('lastname',TextType::class,['label'=>'Achternaam*'])
            ->add('dateofbirth',BirthdayType::class,['label'=>'Geboortedatum*'])
            ->add('loginname',TextType::class,['label'=>'Gebruikersnaam*'])
            ->add('password',TextType::class,['label'=>'Wachtwoord*'])
            ->add('password',TextType::class,['label'=>'Herhaling wachtwoord*'])
            ->add('gender',ChoiceType::class, [
                'label'=>'Man/Vrouw',
                'choices' => [
                    'Man'=>'Man',
                    'Vrouw'=>'Vrouw'
                ],
                'expanded' => true,
                'multiple' => false,
            ])
//            ->add('street',TextType::class,['label'=>'Straat*'])
//            ->add('postal_code',TextType::class,['label'=>'Postcode*'])
//            ->add('city',TextType::class,['label'=>'Stad*:'])
            ->add('emailaddress',TextType::class,['label'=>'Email*:'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Person::class
        ]);
    }
}