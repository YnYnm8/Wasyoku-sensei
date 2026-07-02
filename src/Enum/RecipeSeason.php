<?php

namespace App\Enum;

enum RecipeSeason: string
{
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';
    case WINTER = 'winter';
    case ALL_YEAR = 'all_year';


    public function label(): string
    {
        return match($this) {
            self::SPRING => 'Printemps',
            self::SUMMER => 'Été',
            self::AUTUMN => 'Automne',
            self::WINTER => 'Hiver',
            self::ALL_YEAR => 'Toute l\'année',
        };
    }
}