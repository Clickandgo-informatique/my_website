<?php

namespace App\Form;

use App\Entity\GroupesLinks;
use App\Entity\Links;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path',UrlType::class )
            ->add('icone',TextType::class, ['attr' => ['placeholder' => 'Renseigner que le nom de l\'icône ici.']])
            ->add('parent')
            ->add('titre',TextType::class)
            ->add('groupe', EntityType::class, [
                'class' => GroupesLinks::class,
                'choice_label' => 'titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Links::class,
        ]);
    }
}
