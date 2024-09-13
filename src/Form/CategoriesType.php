<?php

namespace App\Form;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['constraints' => new NotBlank()])
            ->add(
                'parent',
                EntityType::class,
                [
                    'class' => Categories::class,
                    'choice_label' => 'titre','placeholder'=>'-- Aucun parent --',
                    'required'=>false,
                    'query_builder'=>function(CategoriesRepository $cr){
                        return $cr->createQueryBuilder('c')
                        ->orderBy('c.titre','ASC');
                    }
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
