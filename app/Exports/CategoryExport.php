<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;


/**
 * @@implements FromCollection<int, mixed>
 */
class CategoryExport implements FromCollection
{
    /**
    * @return Enumerable
    */
    public function collection():Collection
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
