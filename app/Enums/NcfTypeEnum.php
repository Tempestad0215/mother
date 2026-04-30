<?php

namespace App\Enums;


enum NcfTypeEnum: string
{
    case CREDITO_FISCAL = 'B01';       // Factura con crédito fiscal
    case CONSUMO = 'B02';             // Factura de consumo
    case NOTA_CREDITO = 'B03';        // Nota de crédito
    case NOTA_DEBITO = 'B04';         // Nota de débito
    case EXPORTACION = 'B11';         // Exportaciones
    case EXENTA = 'B12';              // Ventas exentas
    case REGIMEN_ESPECIAL = 'B13';    // Regímenes especiales
    case GUBERNAMENTAL = 'B14';       // Gobierno
    case PROVEEDOR_INFORMAL = 'B15';  // Proveedores informales
    case PAGOS_EXTERIOR = 'B16';      // Pagos en el exterior

}
