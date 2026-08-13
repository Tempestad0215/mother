<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntranceStoreRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {
        return true; // Se puede integrar Gate o $this->user()->can('entrada.crear')
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            // Datos Generales de la Entrada
            'receive_date' => ['required', 'date'],
            'supplier_uuid' => ['required', 'uuid', 'exists:suppliers,uuid'],
            'comment'       => ['nullable', 'string', 'max:500'],

            // Lista de Ítems (Array de productos)
            'items'                  => ['required', 'array', 'min:1'],
            'items.*.code'           => ['required', 'string', 'max:100'],
            'items.*.product_name'   => ['required', 'string', 'max:255'],
            'items.*.warehouse_uuid' => ['required', 'uuid', 'exists:warehouses,uuid'],
            'items.*.cost'           => ['required', 'numeric', 'min:0'],
            'items.*.quantity'       => ['required', 'numeric', 'min:1'],
            'items.*.discount'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.tax_uuid'       => ['nullable', 'uuid', 'exists:taxes,uuid'],
            'items.*.amount'         => ['required', 'numeric', 'min:0'],
        ];
    }


    /**
     * Nombres legibles para los atributos de error.
     */
    public function attributes(): array
    {
        return [
            'supplier_uuid' => 'proveedor',
            'receive_date'  => 'fecha de recepción',
            'comment'       => 'comentario',
            'items' =>  'articulo'
        ];
    }
}
