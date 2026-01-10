<?php

namespace App\Enums;

enum ClientDocumentEnum:string
{
    case Cedula = 'cedula';
    case Pasaporte = 'pasaporte';
    case  RNC = 'rnc';
    case Otro = 'otro';
}
