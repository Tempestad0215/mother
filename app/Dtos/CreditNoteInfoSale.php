<?php

namespace App\Dtos;

class CreditNoteInfoSale extends BaseDto
{

    /**
     * @param string $uuid
     * @param string $code
     * @param string|null $ncf
     * @param float $n_available
     */
    public function __construct(
        public string $uuid,
        public string $code,
        public ?string $ncf,
        public float $n_available
    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            code: $data['code'],
            ncf: $data['ncf'],
            n_available: $data['n_available']
        );

    }

    /**
     * @return CreditNoteInfoSale[]
     */
    public static function formArrayList(array $data): array
    {
        return array_map(fn($item) => self::fromArray($item), $data);
    }
}
