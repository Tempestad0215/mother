<?php

namespace App\Enums;

enum PurchaseStatusEnum:int
{
    case EAPRO = 1;
    case ENPRG = 2;
    case APROB = 3;
    case CANCE = 4;
    case CERRA = 5;
}
