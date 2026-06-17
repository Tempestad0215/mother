<?php

namespace App\Enums;

enum PriceTypeEnum: string
{
    case Price = 'price';
    case MinPrice = 'min_price';
    case PromotionPrice = 'promotion_price';
}
