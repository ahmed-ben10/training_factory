<?php


namespace App\Form;


use App\Entity\Person;
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
            ->add('voornaam',TextType::class,[ 'label'=>'Voornaam*','attr'=>['class'=>'form-check col-lg-10']])
            ->add('tussenvoegsel',TextType::class,['label'=>'Tussenvoegsel','attr'=>['class'=>'form-check col-lg-10'], 'required'=>false])
            ->add('achternaam',TextType::class,['label'=>'Achternaam*','attr'=>['class'=>'form-check col-lg-10']])
            ->add('geboortedatum',DateType::class,['label'=>'Geboortedatum*','attr'=>['class'=>'form-check col-lg-10']])
            ->add('gebruikersnaam',TextType::class,['label'=>'Gebruikersnaam*','attr'=>['class'=>'form-check col-lg-5']])
            ->add('wachtwoord',TextType::class,['label'=>'Wachtwoord*','attr'=>['class'=>'form-check col-lg-5']])
            ->add('herhaling_wachtwoord',TextType::class,['label'=>'Herhaling wachtwoord*','attr'=>['class'=>'form-check col-lg-5']])
            ->add('gender',ChoiceType::class, [
                'label'=>'Man/Vrouw',
                'choices' => [
                    'Man'=>'Man',
                    'Vrouw'=>'Vrouw'
                ],
                'expanded' => true,
                'multiple' => false,
                'required'=>false,
            ])
            ->add('straat',TextType::class,['label'=>'Straat*','attr'=>['class'=>'form-check col-lg-10']])
            ->add('postcode',TextType::class,['label'=>'Postcode*','attr'=>['class'=>'form-check col-lg-2']])
            ->add('stad',TextType::class,['label'=>'Stad:','attr'=>['class'=>'form-check col-lg-10'],'required'=>false])
            ->add('email',TextType::class,['label'=>'Email:','attr'=>['class'=>'form-check col-lg-10'],'required'=>false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class'=>Person::class
//        ]);
    }
}