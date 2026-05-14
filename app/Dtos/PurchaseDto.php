<?php

namespace App\Dtos;

use Carbon\Carbon;

class PurchaseDto extends BaseDto
{
    /**
     * @param string $supplier_uuid
     * @param string $doc_date
     * @param float $tax
     * @param float $amount
     * @param float $sub_total
     * @param float|null $discount
     * @param array $info
     * @param string $user_id
     */
    public function __construct(
        // Datos generales
        public readonly string $supplier_uuid,
        public readonly string $doc_date,
        public readonly float $tax,
        public readonly float $amount,
        public readonly float $sub_total,
        public readonly ?float $discount,

        // Colección de ítems
        /** @var PurchaseRequestItemDto[] */
        public readonly array $info,
        public readonly string $user_uuid
    ) {}

    /**
     * @param $validated
     * @return self
     */
    public static function fromRequest($validated): self
    {
        // Transformamos cada ítem del array a su propio DTO
        $items = array_map(
            fn($item) => PurchaseRequestItemDto::fromArray($item),
            $validated['info']
        );

        return new self(
            supplier_uuid: $validated['supplier_uuid'],
            doc_date: Carbon::parse($validated['doc_date'])->toDateString(),
            tax: (float) $validated['tax'],
            amount: (float) $validated['amount'],
            sub_total: (float) $validated['sub_total'],
            discount: isset($validated['discount']) ? (float) $validated['discount'] : 0,
            info: $items,
            user_uuid:  auth()->id()
        );
    }
}
