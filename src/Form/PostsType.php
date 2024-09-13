<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;
use App\Entity\Tags;
use App\Repository\TagsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['constraints' => [new NotBlank()]])
            ->add('contenu', TextareaType::class)
            ->add('featuredImage', FileType::class, [
                'label' => 'Image mise en avant :',
                'mapped' => false,
                'attr' => ['accept' => 'image/png,image/jpeg,image/webp,image/gif'],
                'constraints' => [
                    new Image(
                        minWidth: 200,
                        maxWidth: 4000,
                        minHeight: 200,
                        maxHeight: 4000,
                        allowPortrait: false,
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                            'image/jpg'
                        ]
                    )
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Categories',
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Mots-clés',
                'class' => Tags::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (TagsRepository $tags) {
                    return $tags->createQueryBuilder('t')
                        ->andWhere('t.parent =:parent')
                        ->setParameter('parent', 'blog')
                        ->orderBy('t.titre', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
