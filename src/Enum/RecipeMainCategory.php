<?php

namespace App\Enum;

enum RecipeMainCategory: string
{
    case MEAT = 'meat';
    case FISH = 'fish';
    case EGG = 'egg';
    case TOFU = 'tofu';
    case VEGETABLE = 'vegetable';

    public function label(): string
    {
        return match($this) {
            self::MEAT => 'Viande',
            self::FISH => 'Poisson',
            self::EGG => 'Œuf',
            self::TOFU => 'Tofu',
            self::VEGETABLE => 'Légumes',
        };
    }
}