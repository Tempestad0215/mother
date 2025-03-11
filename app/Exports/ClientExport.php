<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return Collection
    */
    public function collection()
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
