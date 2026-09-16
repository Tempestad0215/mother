
<style>
    .header-container {
        width: 100%;
        text-align: center;
        font-family: 'Courier New', Courier, monospace;
        color: #000;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .header-title {
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 4px;
        letter-spacing: 1px;
    }

    .company-info {
        font-size: 12px;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .company-info p {
        margin: 1px 0;
    }

    .divider {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    .document-info {
        width: 100%;
        font-size: 12px;
        font-weight: bold;
    }

    .document-table {
        width: 100%;
        border-collapse: collapse;
    }

    .document-table td {
        padding: 2px 0;
        vertical-align: middle;
    }

    .doc-type-row {
        text-align: center;
        text-transform: uppercase;
        font-size: 13px;
        padding-bottom: 4px !important;
    }

    .text-left { text-align: left; }
    .text-right { text-align: right; }
</style>

<div class="header-container">
    <h2 class="header-title">{{ \Illuminate\Support\Str::upper(config('app.name')) }}</h2>

    <div class="company-info">
        @if(!empty($setting->company_id))
            <p>RNC: {{ $setting->company_id }}</p>
        @endif
        @if(!empty($setting->phone))
            <p>TEL: {{ $setting->phone }}</p>
        @endif
        @if(!empty($setting->address))
            <p>{{ $setting->address }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="document-info">
        <table class="document-table">
            <tr>
                <td colspan="2" class="doc-type-row">{{ $documentType }}</td>
            </tr>
            <tr>
                <td class="text-left">Número:</td>
                <td class="text-right">{{ $documentCode }}</td>
            </tr>
            @if(isset($documentNcf))
                <tr>
                    <td class="text-left">NCF:</td>
                    <td class="text-right">{{ $documentNcf }}</td>
                </tr>
            @endif
            <tr>
                <td class="text-left">Fecha:</td>
                <td class="text-right">{{ $documentDate }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>
</div>
