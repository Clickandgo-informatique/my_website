<?php

namespace App\Form;

use App\Entity\Galeries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GaleriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('titre', TextType::class, ['constraints' => [new Assert\NotBlank(message: "Le titre de la galerie d'images est obligatoire")]])
            ->add('type', ChoiceType::class,[
                'choices'=>[
                    'Galerie'=>'galerie',
                    'Carousel'=>'carousel'
                ]
            ])
            ->add('is_active', CheckboxType::class, ['required' => false, 'mapped' => false, 'label' => "Est activée"])
            // ->add('created_at', DateTimeType::class, ['required'=>false,'label' => 'Créée le','attr'=>['class'=>'muted',"disabled"=>true]])
            ->add('images', FileType::class, [
                'mapped' => false,
                'label' => 'Importer images',
                'required' => false,
                'multiple' => true,
            ])
            ->add('primary_background_color', ColorType::class, ['label' => "Couleur primaire de fond", 'attr' => ['class' => 'input-galerie']])
            ->add('gallery_width', NumberType::class, ['mapped' => false, 'html5' => true, 'label' => "Largeur galerie", 'attr' => ["min" => 0, "max" => 100, "step" => 10, "default" => 100, "value" => 100, 'class' => 'input-galerie']])
            ->add('gallery_height', NumberType::class, ['mapped' => false, 'label' => "Hauteur galerie", 'html5' => true, 'attr' => ["min" => 250, "max" => 2000, "step" => 10, "default" => 250, "value" => 250, 'class' => 'input-galerie']])
            ->add('gallery_max_columns', NumberType::class, ['mapped' => false, 'label' => "Colonnes max.", 'html5' => true, 'attr' => ["min" => 1, "max" => 10, "step" => 1, "default" => 10, "value" => 10, 'class' => 'input-galerie']])
            ->add('gallery_gap', NumberType::class, ['mapped' => false, 'label' => "Espacement", 'html5' => true, 'attr' => ["min" => 5, "max" => 50, "default" => 5, "step" => 1, "value" => 5, 'class' => 'input-galerie']])
            ->add('images_border_radius', NumberType::class, ['mapped' => false, 'label' => "Arrondis coins", 'html5' => true, 'attr' => ["min" => 0, "max" => 100, "default" => 5, "value" => 5, 'class' => 'input-galerie']])
            ->add('images_border_width', NumberType::class, ['mapped' => false, 'label' => "Epaisseur bordure", 'html5' => true, 'attr' => ["min" => 0, "max" => 10, "step" => 1, "default" => 2, "value" => 2, 'class' => 'input-galerie']])
            ->add('images_shadow', NumberType::class, ['mapped' => false, 'label' => "Epaisseur ombre", 'html5' => true, 'attr' => ["min" => 0, "max" => 10, "step" => 1, "default" => 2, "value" => 2, 'class' => 'input-galerie']])
            ->add('carousel_speed',NumberType::class,['mapped' => false, 'label' => "Vitesse défilement", 'html5' => true, 'attr' => ["min" => 1000, "max" => 5000, "step" => 1000, "default" => 1000, "value" => 1000, 'class' => 'input-carousel']])
            ->add('carousel_transition',NumberType::class,['mapped' => false, 'label' => "Vitesse transition", 'html5' => true, 'attr' => ["min" => 1000, "max" => 5000, "step" => 1000, "default" => 1000, "value" => 1000, 'class' => 'input-carousel']]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Galeries::class
        ]);
    }
}
