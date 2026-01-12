<?php

namespace App\Enums;


enum NcfTypeEnum: string
{
    case CREDITO_FISCAL = '01';       // Factura con crédito fiscal
    case CONSUMO = '02';             // Factura de consumo
    case NOTA_CREDITO = '03';        // Nota de crédito
    case NOTA_DEBITO = '04';         // Nota de débito
    case EXPORTACION = '11';         // Exportaciones
    case EXENTA = '12';              // Ventas exentas
    case REGIMEN_ESPECIAL = '13';    // Regímenes especiales
    case GUBERNAMENTAL = '14';       // Gobierno
    case PROVEEDOR_INFORMAL = '15';  // Proveedores informales
    case PAGOS_EXTERIOR = '16';      // Pagos en el exterior
    
}
