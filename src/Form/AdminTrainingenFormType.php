<?php


namespace App\Form;


use App\Entity\Training;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminTrainingenFormType extends AbstractType  {
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('naam',TextType::class,['label'=>'Naam*', 'empty_data'=>''] )
            ->add('description',TextareaType::class,['label'=>'Beschrijving*', 'empty_data'=>''] )
            ->add('costs',MoneyType::class,['label'=>'Kosten', 'empty_data'=>''] )
            ->add('duration',TimeType::class,[
                'label'=>'Tijd',
                'widget'=>'choice',
                'empty_data'=>''
            ])
            ->add('image_dir',FileType::class,['label'=>'Kies een foto', 'empty_data'=>''])
         ;
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class'=>Training::class
        ]);
    }
}