<?php

return  [
    /*
     * Fecha de actualziacion
     */
    'document-update' => 30,
    'document-delete' => 15,
    'saleCreditNote' => 30,
    'maxUser' => 10,

    'url_pdf' => env('URL_PDF'),
    'url_rnc' => env('URL_RNC'),

    'user_name_pdf' => env('GOTENBERG_API_BASIC_AUTH_USERNAME'),
    'user_password_pdf' => env('GOTENBERG_API_BASIC_AUTH_PASSWORD'),

    /*
     * Datos de la emprsa
     */
    'company_type' =>  ['BAR','GYM','REPUESTO','SUPERMERCADO','OTRO'],
    'sequence' => ['B01','B02','B03','B04','B11','B12','B13','B14','B15','B16','B17'],
    'sequenceSale' => ['B01','B02','B14','B15','B16'],
    'sequencePurchase' => ['B11','B12','B13','B17'],
    'invoiceType' =>  [
        [
            "type" => "B01",
            "name" => "Credito Fiscal"
        ],
        [
            "type" => "B02",
            "name" => "Consumidor Final"
        ],
        [
            "type" => "B04",
            "name" => "Nota de Credito"
        ],
        [
            "type" => "B14",
            "name" => "Regimen Especial"
        ],
        [
            "type" => "B15",
            "name" => "Gubernamental"
        ],
    ],






    /*
     * Codigo de los modelos
     */
    'accCode' => 'CUN',
    'product' => 'PRO',
    'saleCode' => 'FAC',
    'quoCode' => 'COT',
    'saleRet' => 'DEV',
    'proTrans' => 'TRA',
    'cliCode' => 'CLI',
    'category' => 'CAT',
    'supplier' => 'SUP',
    'creCode' => 'CRE',
    'advCode' =>  'ADV',
    'coCode' => 'COM',
    'deSale' => 'CAN',
    'seqCode' => 'SEQ',
    'couCode' => 'CON',
    'delSale' => 'VCA',
    'exchange' => 'CAM',
    'counter' => 'MON',
    'purchase' => 'COM',
    'creditNote' => 'NCR',


    'msjInvoice' => 'Para devoluciones, traer su factura. Las piezas eléctricas no tienen garantía y la garantía no aplica si la pieza ha sido instalada fuera de nuestro taller.'

];
