<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis {{ $quote->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 24px; }
        .header .company { text-align: right; font-size: 11px; color: #666; }
        .info-block { margin-bottom: 20px; }
        .info-block h2 { font-size: 14px; color: #2563eb; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2563eb; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-row { font-size: 16px; font-weight: bold; background: #f3f4f6; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 10px; text-transform: uppercase; }
        .badge-brouillon { background: #e5e7eb; color: #374151; }
        .badge-envoye { background: #dbeafe; color: #1e40af; }
        .badge-accepte { background: #d1fae5; color: #065f46; }
        .badge-refuse { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>ERP Mini</h1>
            <div>Devis N° <strong>{{ $quote->reference }}</strong></div>
            <div>Date : {{ $quote->created_at->format('d/m/Y') }}</div>
            <div class="badge badge-{{ $quote->status }}">{{ ucfirst($quote->status) }}</div>
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
        <div><strong>{{ $quote->client->name }}</strong></div>
        <div>{{ $quote->client->email }}</div>
        @if($quote->client->phone) <div>{{ $quote->client->phone }}</div> @endif
        @if($quote->client->address) <div>{{ $quote->client->address }}</div> @endif
        @if($quote->client->city) <div>{{ $quote->client->city }}</div> @endif
    </div>

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
            @foreach($quote->items as $item)
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
                <td class="text-right">{{ number_format($quote->total_amount, 2, ',', ' ') }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Devis généré le {{ now()->format('d/m/Y à H:i') }} — ERP Mini © {{ date('Y') }}
    </div>
</body>
</html>