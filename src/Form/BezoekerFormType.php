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
            ->add('achternaam',TextType::class,['attr'=>['label'=>'Achternaam*','class'=>'form-check col-lg-10']])
            ->add('geboortedatum',TextType::class,['attr'=>['label'=>'Geboortedatum*','class'=>'form-check col-lg-10']])
            ->add('gebruikersnaam',TextType::class,['attr'=>['label'=>'Gebruikersnaam*','class'=>'form-check col-lg-10']])
            ->add('wachtwoord',TextType::class,['attr'=>['label'=>'Wachtwoord*','class'=>'form-check col-lg-10']])
            ->add('herhaling_wachtwoord',TextType::class,['attr'=>['label'=>'Herhaling wachtwoord*','class'=>'form-check col-lg-10']])
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
            ->add('straat',TextType::class,['attr'=>['label'=>'Straat*','class'=>'form-check col-lg-10']])
            ->add('postcode',TextType::class,['attr'=>['label'=>'Postcode*','class'=>'form-check col-lg-10']])
            ->add('stad',TextType::class,['attr'=>['label'=>'Stad:','class'=>'form-check col-lg-10'],'required'=>false])
            ->add('email',TextType::class,['attr'=>['email'=>'Email:','class'=>'form-check col-lg-10'],'required'=>false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class'=>Person::class
//        ]);
    }
}