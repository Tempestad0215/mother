<?php

namespace App\Enums;

enum ProductReservationEnum: string
{
    case Active = "Active";
    case Consumed = "Consumed";
    case Released = "Released";

}
