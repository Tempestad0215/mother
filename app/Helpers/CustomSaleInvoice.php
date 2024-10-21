<?php

namespace App\Helpers;


use App\Enums\ProductTransType;
use App\Models\ProTrans;
use App\Models\Sale;
use App\Models\Sequence;
use App\Models\Setting;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use LaravelIdea\Helper\App\Models\_IH_Sale_C;

class CustomSaleInvoice extends Fpdf
{

    /**
     * Propiedades
     * @var _IH_Sale_C|Sale|Sale[]|null
     */
    public Sale|null|array|_IH_Sale_C $saleInfo;
    private string $type;
    //Configuracion
    private Setting $setting;
    //Contructor de la factura



    /**
     * @param int $saleId
     * @param int $height
     */
    public function __construct(int $saleId, int $height = 200)
    {


        //Llmar el metodo del constructor
        parent::__construct('p','mm',array(80,$height));

        //Atrater la informacion de la venta
        $this->saleInfo = Sale::find($saleId);




        //Verificar si existe
        if (!$this->saleInfo)
        {
            //Mensaje indicando que la venta no fue encontrada
            throw ValidationException::withMessages([
                'general' => 'Venta No Encontrada'
            ]);
        }

        //Tomar la configuracion
        $this->setting = Setting::first();
        //Solo tomar hasta la 3 posicion de eso
        $this->type = substr($this->saleInfo->ncf, 0, 3);
        //Agregar la pagina del PDF
        $this->AddPage();
    }

    //Titulo


    /**
     * Cabecera del PDF
     * @return void
     */
    function Header():void
    {
        //Tomar los datos de configracion
        $setting = Setting::first();
        //Tomar los datos del tipo
        $sequence = Sequence::where('type', $this->type)
            ->where('status', true)
            ->first();

//        dd($this->GetPageHeight());


        //Crear el encabezado de la lista
        $this->SetMargins(2,2,2);
        $this->SetFont('Arial', 'B', 14);
        $this->SetY(5);
        $this->Cell(0,5,$setting->name,0,1,'C');
        $this->SetFont('Arial', '', 8);


        $this->SetFont('Arial', '', 8);
        $this->MultiCell(0,4, $setting->address, 0, 'C');
        $this->Cell(18,4, 'RNC :', 0 ,0, 'L') ;
        $this->Cell(0,4,'1-3324316-4',0,1,'L');
        $this->Cell(18,4, 'Correo :', 0 ,0, 'L') ;
        $this->Cell(0,4,$setting->email,0,1,'L');
        $this->Cell(18,4, mb_convert_encoding('Teléfono :','ISO-8859-1','UTF-8'),0 ,0, 'L');
        $this->Cell(0,4,$setting->phone,0,1,'L');
        $this->Ln(3);
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 76, $this->GetY());

        //Aumentar la fuente
        $this->SetFont('Arial', '', 10);


        $poX = 60;
        //Informacion Triburtario
        $this->SetFont('Arial', '', 10);
        $this->Cell(25,5,'Fecha :',0,0,'L') ;
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 5, $this->saleInfo->created_at,0,1);

        if ($this->setting->sequence)
        {
            $this->SetFont('Arial', '', 10);
            $this->Cell(25,5,'NCF :',0,0,'L') ;
            $this->SetFont('Arial', '', 8);
            $this->Cell($poX, 5, $this->saleInfo->ncf,0,1);
            $this->SetFont('Arial', '', 10);
            $this->Cell(25,5,'Valida Hasta :',0,0,'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell($poX, 5, $sequence->date_expire,0,1);
        }else{
            $this->SetFont('Arial', '', 10);
            $this->Cell(25,5,'Factura :',0,0,'L') ;
            $this->SetFont('Arial', '', 8);
            $this->Cell($poX, 5, $this->saleInfo->code,0,1);
        }

        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 76, $this->GetY());


        //Datos del cliente
        $this->SetFont('Arial', '', 10);
        $this->Cell(0,5,'Cliente',0,1,'C');
        $this->Cell(15,5,'Nombre :',0,0,'L');
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(0,5, mb_convert_encoding($this->saleInfo->client_name, 'ISO-8859-1', 'UTF-8'),0,'L' );

        //Si existe la sequencia se muestra el RNC
        if ($this->setting->sequence)
        {
            //Datos de RNC del cliente
            $this->SetFont('Arial', '', 10);
            $this->Cell(15,5,'RNC :',0,0,'L') ;
            $this->SetFont('Arial', '', 8);
            $this->Cell(50, 5, $this->saleInfo->client_rnc,0,1);
        }
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 76, $this->GetY());
    }




    /**
     * Pie de pagina
     * @return void
     *
     */
    function Footer():void
    {
        //Posicion  de los calculos
        $wC = array(25,50);
        //Posicion del footer
        $this->SetY(-75);
        $this->SetX($wC[0]);

        //Crear los datos de totaltes de la factura
        $this->Cell(30, 5,mb_convert_encoding('Cantidad De Artículo :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, count($this->saleInfo->infoSale),0,1);

        //Sub total
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Sub Total :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->sub_total,2),0,1);


        //Descuento
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Descuento :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->discount_amount),0,1);


        //Gravado
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Gravado :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->amount - $this->saleInfo->tax),0,1);


        //Itbis
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Itbis :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->tax,2),0,1);

        //Total
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Total :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->amount,2),0,1);


        //Pago Cont
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Pago Con :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->received,2),0,1);


        //Devuelta
        $this->SetX($wC[0]);
        $this->Cell(30, 5,mb_convert_encoding('Devuelta :','Iso-8859-1','UTF-8'));
        $this->Cell(0,5, number_format($this->saleInfo->returned,2),0,1);





        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 76, $this->GetY());
        $this->Ln(3);
        $this->SetFont('Arial', '', 8);
        //Quien le atedio
        $this->Cell(20,5,  mb_convert_encoding('Le Atendió :','ISO-8859-1','UTF-8'),0,0,'L');
        $this->Cell(0,5,  mb_convert_encoding(Auth::user()->name,'ISO-8859-1','UTF-8'),0,1,'L');

        //Tipo de pago
        $this->Cell(20,5,  mb_convert_encoding('Tipo Pago :','ISO-8859-1','UTF-8'),0,0,'L');
        $this->Cell(0,5,  mb_convert_encoding($this->saleInfo->type_payment->name,'ISO-8859-1','UTF-8'),0,1,'L');

        //Fehca de impresion
        $this->Cell(20,5, mb_convert_encoding('Impresión :','ISO-8859-1','UTF-8'),0,0,'L');
        $this->Cell(0,5, Carbon::now(), 0,1);

        $this->Ln(3);
        //Mensaje de agradecimiento
        $this->MultiCell(0,3, mb_convert_encoding(config('appconfig.msjInvoice'),'ISO-8859-1', 'UTF-8'), 0, 'C');




    }


    /**
     * Obtiene el PDF en BASE 64
     * @return string
     */
    public function getPDF():string
    {
        //Craer el array la cabecera de los datos
        $header = array('Cant.','Descripción','Importe');
        //Tomar los datos de la venta de esa venta
        $data = collect($this->saleInfo->infoSale->where('type',ProductTransType::VENTAS));
        //ancho de la columna
        $w = array(15,40,21);

        //Crear la cabecera
        for ($i = 0; $i < count($header); $i++)
        {
            $this->Cell($w[$i],7,mb_convert_encoding($header[$i],'ISO-8859-1','UTF-8'),1,0,'C');
        }
        $this->Ln();

        //Crea los datos del PDF
        $data->map(function(ProTrans $item) use(&$w){
            //Cantida del productos
            $this->Cell($w[0],6, number_format($item->stock,2),0,0,'L');

            //Descripcion
            $this->Cell($w[1],6, $item->product_name,0,0,'L');
            $this->SetXY($this->GetX() - $w[1], $this->GetY() + 4);
            $this->Cell($w[1],6, number_format($item->price,2),0,0,'L');

            //Datos totales
            $this->SetXY($this->GetX(), $this->GetY() - 4);
            $this->MultiCell(0,5,number_format($item->amount,2),0 ,'L');


            //Crear la linea divisora
            $this->SetY($this->GetY() + 5);
            $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 76, $this->GetY());
        });





        //DEvolver el pedf
        return base64_encode($this->Output('S', true, true));
    }

}
