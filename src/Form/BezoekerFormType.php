<?php


namespace App\Form;


use App\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BezoekerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('voornaam',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('tussenvoegsel',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('achternaam',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('geboortedatum')
            ->add('gebruikersnaam',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('wachtwoord',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('herhaling_wachtwoord',TextType::class,['attr'=>['class'=>'form-check']])
            ->add('gender')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class'=>Person::class
//        ]);
    }
}