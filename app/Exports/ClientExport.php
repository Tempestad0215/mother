<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientExport implements FromCollection
{
    /**
    * @return Enumerable
    */
    public function collection(): Collection
    {
        return Client::all();
    }


    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            'Codigo',
            'Nombre',
            'Documento Tipo',
            'Telefono',
            'Correo',
            'Direccion',
            'Tipo Pago',
            'Estado',
        ];
    }



    public function map($row): array
    {
        return [
            $row->id,
            $row->code,
            $row->name,
            $row->document->name,
            $row->phone,
            $row->email,
            $row->address,
            $row->type->name,
            $row->status,
        ];
    }
}
