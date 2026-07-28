<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Enum\RecipeSeason;
use App\Enum\RecipeLevel;
use App\Enum\RecipeMainCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
            ])
            ->add('step', TextareaType::class, [
                'label' => false,
            ])
            ->add('season', EnumType::class, [
                'class' => RecipeSeason::class,
                'choice_label' => fn (RecipeSeason $season) => $season->label(),
                'label' => false,
            ])
            ->add('time', TextType::class, [
                'label' => false,
                'attr' => ['type' => 'number', 'min' => 1],
            ])
            ->add('level', EnumType::class, [
                'class' => RecipeLevel::class,
                'choice_label' => fn (RecipeLevel $level) => $level->label(),
                'label' => false,
            ])
            ->add('mainCategory', EnumType::class, [
                'class' => RecipeMainCategory::class,
                'choice_label' => fn (RecipeMainCategory $category) => $category->label(),
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}