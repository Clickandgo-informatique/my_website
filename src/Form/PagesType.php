<?php

namespace App\Form;

use App\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class)
            ->add('sous_titre',TextType::class)
            ->add('ordre',IntegerType::class)
            ->add('etat',ChoiceType::class,['choices'=>['Publiée'=>'Publiée','Brouillon'=>'Brouillon','Archivée'=>'Archivée']])
            ->add('created_at', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('slug',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
