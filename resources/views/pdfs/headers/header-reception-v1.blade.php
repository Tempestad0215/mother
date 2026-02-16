@php
    /** @var App\Models\Setting $setting */
    /** @var App\Models\PurchaseReceipts $receipts */
    $logo = public_path('/logo-min.jpeg')
@endphp

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        display: block;
        margin: 6mm 12mm 6mm 12mm;
        width: 100%;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        color: #333;
    }

    .header-info {
        display: flex;
        gap: 20px;
        justify-content: space-between;
        justify-items: center;
    }

    .app-name {
        flex: 1;
    }

    .company-info h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 2mm;
    }

    .company-info p {
        font-size: 14px;
        color: #666;
    }


    .info-block h3 {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 3mm;
    }

    .info-block p {
        font-size: 11pt;
        margin-bottom: 1mm;
    }

    .info-block .name {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 2mm;
    }

    .info-block .detail {
        color: #666;
    }
</style>

<div class="container">
    <div style="font-size:  10rem; ">
        <div class="header-info">
            <div style="max-width: 100px">
                @inlinedImage($logo)
            </div>
            <div class="app-name">
                <h1>{{$setting->name}}</h1>
                <p>{{$setting->company_id}}</p>
            </div>
            <div class="info-nm">
                <h3>
                    Recepcion N.
                </h3>
                <p class="mt-2 text-sm">REC - {{ str_pad($receipts->id, 8 , "0", STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <!-- Company & Client Info -->
    <div class="info">

        <!-- Client Info -->
        <div>
            <h3>Información Proveedor:</h3>
            <div>
                <p class="font-bold text-lg">{{$receipts->supplier->company_name}}</p>
                <p class="text-gray-600">RNC: 987-6543210-9</p>
                <p class="text-gray-600">Calle Secundaria #456</p>
                <p class="text-gray-600">Tel: {{$receipts->supplier->phone}}</p>
                <p class="text-gray-600">Correo: {{$receipts->supplier->email}}</p>
            </div>
        </div>
    </div>
</div>
