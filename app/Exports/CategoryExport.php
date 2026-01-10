<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Category::all();
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
            'Descripcion',
            'Estado',
        ];
    }



    public function map($row): array
    {
        return [
            $row->id,
            $row->code,
            $row->name,
            $row->description,
            $row->status,
        ];
    }
}
