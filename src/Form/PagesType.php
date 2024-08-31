<?php

namespace App\Form;

use App\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder  
        ->add('id',HiddenType::class,['required'=>false,'disabled'=>true])         
            ->add('isPageAccueil', CheckboxType::class, ['required' => false, 'label' => "Est la page d'accueil", 'label_attr' => ['class' => 'switch'], 'attr' => ['class' => 'toggle-switch']])
            ->add('titre', TextType::class)
            ->add('sous_titre', TextType::class)
            ->add('ordre', IntegerType::class, ['required' => false,'disabled'=>true])
            ->add('etat', ChoiceType::class, ['choices' => ['Publiée' => 'Publiée', 'Brouillon' => 'Brouillon', 'Archivée' => 'Archivée'], "expanded" => true,'attr' => ['class' => 'toggle-group']])
            // ->add('created_at', DateTimeType::class, [
            //     'widget' => 'single_text', 'label' => 'Créée le','required'=>false,
            //     'attr' => ['class' => 'muted', 'disabled' => true]
            // ])
            // ->add('updated_at', DateTimeType::class, [
            //     'widget' => 'single_text', 'label' => 'Modifiée le','required'=>false,
            //     'attr' => ['class' => 'muted', 'disabled' => true]
            // ])
            ->add('slug', TextType::class, ['required' => false])
            ->add('sectionsPages', CollectionType::class, [
                'entry_type' => SectionsPagesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['name' => 'section-page[]']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
