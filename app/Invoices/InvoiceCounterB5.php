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


    public function __construct(int $id)
    {
        //Para poner los datos del counter
        $this->moneyCounter = MoneyCounter::find($id);
        $this->setting = Setting::first();

        //crear la altura de los datos
        $format = [72, 150];


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

        //Titulo de la ventana
        $this->setY('5');
        $this->Cell(0, 5, $this->setting->name, 0, 1, 'C', 0, '', 0, false, 'M' );

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


        $this->setFont('times', 'B', 14);
        //Titulo de la ventana
        $this->Cell(0, 5, 'Conteo De Papeleta', 0, 1, 'C', 0, '', 0, false, 'M' );



        $this->setFont('helvetica', '', 10);
        //Crea el encabezado de la tabla
        $headerTable = <<<EOD
            <table
                style="border: 1px solid black;">
                <tr
                    >
                    <th
                        style="width:30mm; border: 1px solid black;" >
                        Cant.</th>

                    <th
                         style="width:38mm; border: 1px solid black;">
                        Importe</th>
                </tr>
            </table>
            EOD;


        //Crea el encabezado de los productos
        $this->writeHTML($headerTable, 1, false, true);

        $this->headerEnd = $this->GetY();
    }




    public function getData()
    {
        $this->setMargins(4, 2, 2);
        $this->setFont('helvetica', '', 10);
        $dataTable = <<<EOD
            <table>
                <tr>
                    <td>{$this->moneyCounter->coin_first} * 1</td>
                    <td>{$this->moneyCounter->coin_first}</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_second} * 5</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_second,5) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_third} * 10</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_third,10) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_fourth} * 25</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_fourth,25) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_fifth} * 50</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_fifth,50) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_sixth} * 100</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_sixth,100) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_seventh} * 200</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_seventh,200) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_eighth} * 500</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_eighth,500) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_ninth} * 1,000</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_ninth,1000) }</td>
                </tr>
                <tr>
                    <td>{$this->moneyCounter->coin_tenth} * 2,000</td>
                    <td>{$this->resultCount($this->moneyCounter->coin_tenth,2000) }</td>
                </tr>

            </table>
        EOD;

        $this->setY($this->headerEnd - 3);
        $this->writeHTML($dataTable, true, false, true);


        return $this->Output('doc.pdf','I');
    }




    private function resultCount(float $coint, int $factor)
    {
        return number_format($coint * $factor,2);
    }


}
