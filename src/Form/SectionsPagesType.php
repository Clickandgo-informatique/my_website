<?php

namespace App\Form;

use App\Entity\Galeries;
use App\Entity\SectionsPages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionsPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titre', TextType::class, ['attr' => ['id' => 'inputTitre', 'class' => 'titre-section']])
            ->add('contenu', TextareaType::class, ['attr' => ['class' => 'contenu-section']])
            ->add('galerie', EntityType::class, ['class' => Galeries::class, 'required' => false, 'empty_data' => null, 'placeholder' => '-- Choisissez une galerie --', 'choice_label' => 'getDetailsGalerie', 'label' => 'Galerie', 'attr' => ['class' => 'select-galeries']])
;
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
