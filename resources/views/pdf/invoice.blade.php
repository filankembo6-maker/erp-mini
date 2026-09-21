<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #16a34a; padding-bottom: 15px; }
        .header h1 { color: #16a34a; margin: 0; font-size: 24px; }
        .header .company { text-align: right; font-size: 11px; color: #666; }
        .info-block { margin-bottom: 20px; }
        .info-block h2 { font-size: 14px; color: #16a34a; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #16a34a; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-row { font-size: 16px; font-weight: bold; background: #f0fdf4; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 10px; text-transform: uppercase; }
        .badge-impayee { background: #fef3c7; color: #92400e; }
        .badge-payee { background: #d1fae5; color: #065f46; }
        .badge-annulee { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>ERP Mini</h1>
            <div>Facture N° <strong>{{ $invoice->reference }}</strong></div>
            <div>Date : {{ $invoice->created_at->format('d/m/Y') }}</div>
            <div class="badge badge-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</div>
        </div>
        <div class="company">
            <strong>ERP Mini SARL</strong><br>
            Brazzaville, Congo<br>
            contact@erp-mini.com<br>
            +242 06 00 00 00
        </div>
    </div>

    <div class="info-block">
        <h2>CLIENT</h2>
        <div><strong>{{ $invoice->client->name }}</strong></div>
        <div>{{ $invoice->client->email }}</div>
        @if($invoice->client->phone) <div>{{ $invoice->client->phone }}</div> @endif
        @if($invoice->client->address) <div>{{ $invoice->client->address }}</div> @endif
        @if($invoice->client->city) <div>{{ $invoice->client->city }}</div> @endif
    </div>

    @if($invoice->quote)
        <div class="info-block">
            <div style="font-size: 11px; color: #666;">Référence devis : <strong>{{ $invoice->quote->reference }}</strong></div>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>SKU</th>
                <th class="text-right">Qté</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->sku }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->unit_price, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 2, ',', ' ') }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Facture générée le {{ now()->format('d/m/Y à H:i') }} — ERP Mini © {{ date('Y') }}
    </div>
</body>
</html>
