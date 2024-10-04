<?php

return  [
    /*
     * Fecha de actualziacion
     */
    'document-update' => 30,
    'document-delete' => 15,
    'maxUser' => 10,




    /*
     * Datos de la emprsa
     */
    'company_type' =>  ['BAR','GYM','RESPUESTO','SUPERMERCADO','OTRO'],
    'sequence' => ['B01','B02','B03','B04','B11','B12','B13','B14','B15','B16','B17'],
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
    'proCode' => 'PRO',
    'saleCode' => 'FACT',
    'quoCode' => 'COT',
    'transCode' => 'TRA',
    'cliCode' => 'CLI',
    'catCode' => 'CAT',
    'supCode' => 'SUP',
    'creCode' => 'CRE',
    'advCode' =>  'ADV',
    'coCode' => 'COM',
    'deSale' => 'CAN',
    'seqCode' => 'SEQ',

];
