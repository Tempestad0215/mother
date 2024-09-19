<?php

namespace App\Enums;

Enum ProductTransType:string {
    CASE  ENTRADA = 'entrada';
    CASE VENTAS = 'ventas';
    CASE  SALIDA = 'salida';
    CASE  INTERNO = 'interno';
    CASE  AJUSTE = 'ajuste';
    CASE  OTROS = 'otros';
}
