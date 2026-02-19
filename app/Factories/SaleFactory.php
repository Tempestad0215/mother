<?php

namespace App\Factories;

use App\Dtos\SaleDto;
use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SaleFactory extends BaseFactory
{
    /**
     * Crea un SaleItemDto desde un array (por ejemplo, un item de info_sale del request).
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): SaleDto
    {
        return new SaleDto(
            discount_amount: $data['discount_amount'],
            tax: $data['tax'],
            sub_total: $data['sub_total'],
            amount: $data['amount'],
            type: SaleTypeEnum::from($data['type']),
            type_payment: PaymentTypeEnum::from($data['type_payment']),
            received: $data['received'],
            returned: $data['returned'],
            close_table: $data['close_table'],
            credit_note_amount: $data['credit_note_amount'] ?? 0,
            ncf: $data['ncf'],
            invoice_type: $data['invoice_type'],
            client_name: $data['client_name'],
            client_rnc: $data['client_rnc'],
            client_id: $data['client_id'],
            credit_notes: $data['credit_notes'],
            comment: $data['comment'],
        );
    }

    /**
     * @param StoreProductSaleRequest $request
     * @param Setting $setting
     * @return SaleDto
     */
    public static function fromRequest(StoreProductSaleRequest $request, Setting $setting): SaleDto
    {

        //obtener notas de credito
        $creditNotes = $request->get('credit_notes');
        //Sacar los IDS
        $ids = array_column($creditNotes, 'id');

        $saleData = $request->validated();
        $saleData['client_id'] = $request->get('client_id') ?: null;
        $saleData['invoice_type'] = $setting->sequence ? $request->get('invoice_type') : null;
        $saleData['credit_notes'] = $ids;


        return self::fromArray($saleData);

    }


}
