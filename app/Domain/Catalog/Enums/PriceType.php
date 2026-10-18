<?php

namespace App\Domain\Catalog\Enums;

enum PriceType: string
{
    case Retail = 'retail';
    case Wholesale = 'wholesale';
}
