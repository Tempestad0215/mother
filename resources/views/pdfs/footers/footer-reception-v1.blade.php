<style>
    .container{
        margin: 0 12mm 0 12mm;
    }
    .totals{
        border: 1px solid gray;
        padding: 2mm;
        border-radius: 2mm;
    }
    .sub-total, .tax, .discount, .amount{
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.5mm 2mm;
    }
    .amount {
        color: #1e40af;
        font-size: 20px;
    }
    .comment{
        margin-top: 5mm;
        min-height: 30mm;
    }
</style>
@php
    /** @var App\Models\PurchaseReceipts $receipts */
@endphp
<div class="container">
    <!-- Totals -->
    <div class="totals" >
        <div >
            <div >
                <div class="sub-total" >
                    <span>Subtotal:</span>
                    <span >
                        {{$receipts->sub_total}}
                    </span>
                </div>
                <div class="tax" >
                    <span>ITBIS:</span>
                    <span class="font-semibold">
                        {{$receipts->tax}}
                    </span>
                </div>
                <div class="discount" >
                    <span>Descuento:</span>
                    <span >
                        {{$receipts->discount}}
                    </span>
                </div>
                <div class="amount" >
                    <span>Total:</span>
                    <span class="text-blue-700">
                        {{$receipts->amount}}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 p-8 border-t">
        <div class="comment">
            <h3>Comentario :</h3>
            <p>{{$receipts->comment}}</p>
        </div>
    </div>


</div>

