<?php


namespace App\Form;


use App\Entity\Lesson;
use App\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('date',DateType::class, ['label'=>'Datum'])
            ->add('time',TimeType::class, ['label'=>'Tijd'])
            ->add('training',EntityType::class, [
                'label'=>'Sport',
                'class'=>Training::class,
                'choice_label'=>'naam'
            ])
            ->add('location',TextType::class, ['label'=>'Lokaal'])
            ->add('maxPersons',NumberType::class, ['label'=>'Maximaal aantal deelnemers:'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class'=>Lesson::class
        ]);
    }
}