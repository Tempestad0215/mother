<?php

namespace App\Dtos;

use App\Enums\PaMovementType;
use App\Http\Requests\EntranceStoreRequest;
use Carbon\Carbon;

class EntranceDto extends BaseDto
{
    /**
     * @param string $supplier_uuid
     * @param Carbon $document_date
     * @param string $comment
     * @param EntranceItemDto[] $items
     * @param float $sub_total
     * @param PaMovementType $pa_type
     * @param float $total
     * @param float $tax
     */
    public function __construct(
        public string $supplier_uuid,
        public Carbon $document_date,
        public string $comment,
        public array $items,
        public float $sub_total,
        public PaMovementType $pa_type,
        public float $total,
        public float $tax


    ) {}

    /**
     * Crea una instancia desde un array o desde el Request validado
     * * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {

        // Convertir items a DTOs
        $items = EntranceItemDto::fromArrayList($data['items'] ?? []);
        // Calcular totales usando BC Math
        $totals = self::calculateTotals($items);

        return new self(
            supplier_uuid: (string) $data['supplier_uuid'],
            document_date: Carbon::parse($data['document_date']),
            comment: $data['comment'] ?? '',
            items: $items,
            sub_total: (float) $totals['sub_total'],
            pa_type: PaMovementType::from($data['pa_type'] ?? 'purchase_entry'),
            total: (float) $totals['total'],
            tax: (float) $totals['tax']
        );
    }

    /**
     * Calcular totales usando BC Math para alta precisión
     */
    private static function calculateTotals(array $items): array
    {
        $subTotal = '0';
        $taxTotal = '0';
        $total = '0';

        foreach ($items as $item) {
            // Cada item ya tiene sus cálculos internos
            $subTotal = bcadd($subTotal, (string) $item->subtotal, 4);
            $taxTotal = bcadd($taxTotal, (string) $item->tax, 4);
            $total = bcadd($total, (string) $item->total, 4);
        }

        return [
            'sub_total' => $subTotal,
            'tax' => $taxTotal,
            'total' => $total,
        ];
    }

    /**
     * Helper alternativo para instanciar directamente desde el Request de Laravel
     * * @param EntranceStoreRequest $request
     * @return self
     */
    public static function fromRequest(EntranceStoreRequest $request): self
    {
        return self::fromArray($request->validated());
    }
}
