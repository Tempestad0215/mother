<?php

namespace App\Invoices;

class General extends \TCPDF
{

    public function __construct()
    {
        // Llama al constructor de TCPDF
        parent::__construct();

        $this->SetMargins(2, 2, 2);
    }


    public  function Title(
        string $name,
        string $address,
        string $company_id,
        string $email,
        string $phone):void
    {


        // Asegúrate de que haya una página activa
        if ($this->getNumPages() === 0) {
            $this->AddPage(); // Agrega una página si no existe ninguna
        }


        $this->Cell(0, 5, $name, 0, 1, 'C', 0, '', 0, false, 'M' );

        //Direccions
        $this->setFont('helvetica', '', 10);
        $this->MultiCell(0,0, $address, 0, 'C', false, 1, '', '', true,true);

        $this->Ln(3);
        $this->Line($this->GetX(),$this->GetY(),$this->GetX() + 68,$this->GetY());
        //Rnc
        $this->Cell(20,5, 'RNC :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $company_id, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Correo :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $email, 0, 1, 'L', 0, '', 0, false, '' );

        //Telefono
        $this->Cell(20,5, 'Teléfono :', 0, 0, 'L', false, '', '', true,'');
        $this->Cell(0, 5, $phone, 0, 1, 'L', 0, '', 0, false, '' );

        //Crear linea divisora
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+ 68,$this->GetY());
    }

}
