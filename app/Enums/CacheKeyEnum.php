<?php

namespace App\Enums;

enum CacheKeyEnum: string
{
    case Warehouses = 'app_warehouses';
    case Settings = 'app_settings';
    case Categories = 'app_categories';
    case Tax = 'app_taxes';
}
