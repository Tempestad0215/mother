<?php

namespace App\Invoices;

use App\Models\MoneyCounter;
use App\Models\Setting;
use TCPDF;

class InvoiceCounterB5 extends TCPDF
{

    //contador
    protected MoneyCounter $moneyCounter;
    protected Setting $setting;
    private float $headerEnd;
    private int $line = 68;

    /**
     * Contructor del metodo
     * @param MoneyCounter $moneyCounter
     */
    public function __construct(
        MoneyCounter $moneyCounter,
    )
    {
        //Para poner los datos del counter
        $this->moneyCounter = $moneyCounter;
        $this->setting = Setting::first();

        //crear la altura de los datos
        $format = [72, 230];


        //Llamar el constructor del padre
        parent::__construct('P','mm',$format);

        //Colocar el magen
        $this->SetMargins(2, 2, 2);

        //Incluir la pagina
        $this->AddPage();


    }



    /**
     * Cabecera de la pagina
     * @return void
     */
    public function Header():void{


        $this->setFont('times', 'B', 14);


//        //Titulo de la ventana
        $this->setY('5');
        $this->Cell(0, 5, $this->setting->name, 0, 1, 'C');

        //Direccions
        $this->setFont('helvetica', '', 10);
        $this->MultiCell(0,0, $this->setting->address, 0, 'C', false, 1, '', '', true,true);

        $this->Ln(3);
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());
        //Rnc
        $this->Cell(20,5, 'RNC :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->company_id, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Correo :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->email, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Teléfono :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $this->setting->phone, 0, 1, 'L', 0, '', 0, false, '' );

        //Crear linea divisora
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+$this->line,$this->GetY());

        //Crae linea con dise;o
        $this->Ln(5);


        //Fecha de informee
        $this->Cell(20,5,'Desde :');
        $this->Cell(30,5,$this->moneyCounter->from ,0,1);

        $this->Cell(20,5,'Hasta :');
        $this->Cell(30,5,$this->moneyCounter->to,0,1);



        $this->setFont('times', 'B', 14);
        //Titulo de la ventana
        $this->Cell(0, 5, 'Conteo De Papeleta', 0, 1, 'C');



        $this->setFont('helvetica', '', 10);
        //Crea el encabezado de la tabla

        $this->headerEnd = $this->GetY();
    }


    /**
     * @return string
     */
    public function setData():string
    {
        //Para colocar el inicio
        $this->setY($this->headerEnd + 5);
        //Tipo de letras
        $this->setFont('helvetica', '', 10);

        $wType = 35; //Ancho de la ventana
        $wQuantity = 33; //Ancho de la cantidad

        // Agregar la datos
        $this->Cell($wType,5, 'Tipo', 1, 0, 'C', 0);
        $this->Cell($wQuantity,5, 'Cantidad', 1, 1, 'C', 0);

        //Colocar el tipod e monedaa

        $this->Cell($wType,5, 'Moneda de 1', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_first,2), 0, 1, 'C', 0);

        //Moneda de 5
        $this->Cell($wType,5, 'Moneda de 5', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_second,2), 0, 1, 'C', 0);

        //Moneda de 10
        $this->Cell($wType,5, 'Moneda de 10', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_third,2), 0, 1, 'C', 0);

        //Moneda de 25
        $this->Cell($wType,5, 'Moneda de 25', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_fourth,2), 0, 1, 'C', 0);

        //Moneda de 50
        $this->Cell($wType,5, 'Papeleta de 50', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_fifth,2), 0, 1, 'C', 0);

        //Moneda de 100
        $this->Cell($wType,5, 'Papeleta de 100', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_sixth,2), 0, 1, 'C', 0);

        //Moneda de 200
        $this->Cell($wType,5, 'Papeleta de 200', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_seventh,2), 0, 1, 'C', 0);

        //Moneda de 500
        $this->Cell($wType,5, 'Papeleta de 500', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_eighth,2), 0, 1, 'C', 0);

        //Moneda de 1000
        $this->Cell($wType,5, 'Papeleta de 1,000', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_ninth,2), 0, 1, 'C', 0);

        //Moneda de 1000
        $this->Cell($wType,5, 'Papeleta de 2,000', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->coin_tenth,2), 0, 1, 'C', 0);


        //Otros ingresos
        $this->setFont('Times','U',14);
        $this->Cell(0,5, 'Otros Ingresos', 0, 1, 'C', 0);



        //Tarjetas
        $this->setFont('Helvetica','',10);
        $this->Cell($wType,5, 'Tarjeta', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->card,2), 0, 1, 'C', 0);


        //Tarjetas
        $this->Cell($wType,5, 'Tranferencia', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->transfer,2), 0, 1, 'C', 0);

        //Cheque
        $this->Cell($wType,5, 'Cheque', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->check,2), 0, 1, 'C', 0);


        //Otros ingresos
        $this->Cell($wType,5, 'Otros Ingresos', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->other_income,2), 0, 1, 'C', 0);


        //Otros Gastos
        $this->setFont('Times','U',14);
        $this->Cell(0,5, 'Otros Gastos', 0, 1, 'C', 0);


        //Tarjetas
        $this->setFont('Helvetica','',10);
        $this->Cell($wType,5, 'Gatos', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->expenses,2), 0, 1, 'C', 0);


        //Retiro de caja
        $this->Cell($wType,5, 'Retiro Caja', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->cash_withdrawals,2), 0, 1, 'C', 0);

        //Devoluciones
        $this->Cell($wType,5, 'Devoluciones', 0, 0, 'L', 0);
        $this->Cell($wQuantity,5,number_format($this->moneyCounter->refund,2), 0, 1, 'C', 0);

        //Otros Gastos
        $this->Cell($wType,5, 'Otros Gatos', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5, number_format($this->moneyCounter->other_expenses,2), 0, 1, 'C', 0);



        //Balance Inicial
        $this->setFont('Times','U',14);
        $this->Cell(0,5, 'Balance Inicial', 0, 1, 'C', 0);
        $this->setFont('helvetica','',10);
        $this->Cell(0,5, number_format($this->moneyCounter->opening_balance,2)  , 0, 1, 'C', 0);


        //Titulod e resultado
        $this->setFont('Times','U',14);
        $this->Cell(0,5, 'Resultado', 0, 1, 'C', 0);


        //Tarjetas
        $this->setFont('Helvetica','',10);
        $this->Cell($wType,5, 'Ingresos', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5, number_format($this->moneyCounter->total_coin,2), 0, 1, 'C', 0);

        //Otros ingresos
        $this->Cell($wType,5, 'Otros Ingresos', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5, number_format( $this->moneyCounter->total_other_coin, 2), 0, 1, 'C', 0);

        //Gastos
        $this->Cell($wType,5, 'Gastos', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5, number_format($this->moneyCounter->total_expenses,2), 0, 1, 'C', 0);

        //Difrencia
        $this->Cell($wType,5, 'Beneficios', 0, 0, 'L', 0 );
        $this->Cell($wQuantity,5, number_format($this->moneyCounter->diff,2), 0, 1, 'C', 0);


        return $this->Output('test.pdf','S');
    }




}
