<?php

namespace App\Enums;

enum ClientDocumentEnum:string
{
    case CEDULA = 'cedula';
    case PASAPORTE = 'pasaporte';
    case  RNC = 'rnc';
    case OTRO = 'otro';
}
