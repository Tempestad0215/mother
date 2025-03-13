<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Supplier::all();
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            'Codigo',
            'Representante',
            'Empresa',
            'Tipo Pago',
            'Telefono',
            'Email',
            'Cuenta Bancaria',
            'Estado',
        ];
    }



    public function map($row): array
    {
        return [
            $row->id,
            $row->code,
            $row->contact,
            $row->company_name,
            $row->type_payment->name,
            $row->phone,
            $row->email,
            $row->account_bank,
            $row->status,
        ];
    }
}
