<?php

namespace App\Form;

use App\Entity\Pages;
use App\Entity\SectionsPages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionsPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
     
        $builder
            ->add('titre', TextType::class)
            ->add('contenu', TextareaType::class);         
            // ->add('created_at', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Créée le',
            //     'input' => 'datetime_immutable',
            //     'attr' => ['class' => 'muted', 'disabled' => true]
            // ])
            // ->add('updated_at', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Modifiée le',
            //     'input' => 'datetime_immutable',
            //     'attr' => ['class' => 'muted', 'disabled' => true]
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionsPages::class,
            
        ]);        
    }
}
