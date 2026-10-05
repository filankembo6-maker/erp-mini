<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use App\Models\StockMovement;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@erp.com')->first();

        if (!$admin) {
            $this->command->warn('Utilisateur admin introuvable.');
            return;
        }

        $products = [
            ['name' => 'Ordinateur portable HP 15', 'sku' => 'HP-15-001', 'description' => 'Core i5, 8 Go RAM, 512 Go SSD', 'price' => 385000, 'stock_quantity' => 12, 'stock_alert' => 5],
            ['name' => 'Imprimante Canon LBP223', 'sku' => 'CAN-LBP223', 'description' => 'Laser monochrome', 'price' => 185000, 'stock_quantity' => 8, 'stock_alert' => 3],
            ['name' => 'Clavier sans fil Logitech', 'sku' => 'LOG-K400', 'description' => 'Clavier AZERTY Bluetooth', 'price' => 25000, 'stock_quantity' => 45, 'stock_alert' => 10],
            ['name' => 'Souris optique Logitech M90', 'sku' => 'LOG-M90', 'description' => 'Souris USB 1000 dpi', 'price' => 7500, 'stock_quantity' => 60, 'stock_alert' => 15],
            ['name' => 'Écran Dell 24 pouces', 'sku' => 'DELL-24-01', 'description' => 'Full HD 1920x1080', 'price' => 145000, 'stock_quantity' => 3, 'stock_alert' => 5],
            ['name' => 'Onduleur APC 650VA', 'sku' => 'APC-650', 'description' => 'Protection secteur', 'price' => 65000, 'stock_quantity' => 15, 'stock_alert' => 5],
            ['name' => 'Disque dur externe 1To', 'sku' => 'HDD-EXT-1T', 'description' => 'USB 3.0', 'price' => 55000, 'stock_quantity' => 22, 'stock_alert' => 8],
            ['name' => 'Cartouche encre HP 305', 'sku' => 'HP-305-N', 'description' => 'Noir', 'price' => 18500, 'stock_quantity' => 2, 'stock_alert' => 10],
            ['name' => 'Papier A4 ramette 500 feuilles', 'sku' => 'PAP-A4-500', 'description' => '80g/m²', 'price' => 4500, 'stock_quantity' => 180, 'stock_alert' => 50],
            ['name' => 'Webcam Logitech C270', 'sku' => 'LOG-C270', 'description' => 'HD 720p', 'price' => 32000, 'stock_quantity' => 0, 'stock_alert' => 5],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        $clients = [
            ['name' => 'Entreprise Mbongo SARL', 'email' => 'contact@mbongo.cg', 'phone' => '+242 06 512 34 56', 'address' => '12 Avenue de la Paix', 'city' => 'Brazzaville'],
            ['name' => 'Cabinet Nkodia & Associés', 'email' => 'cabinet@nkodia.cg', 'phone' => '+242 05 478 92 10', 'address' => '45 Rue Mbochis', 'city' => 'Brazzaville'],
            ['name' => 'Clinique Sainte-Marie', 'email' => 'info@clinique-sm.cg', 'phone' => '+242 06 723 45 89', 'address' => 'Boulevard Denis Sassou', 'city' => 'Brazzaville'],
            ['name' => 'École Les Palmiers', 'email' => 'direction@palmiers.cg', 'phone' => '+242 05 234 56 78', 'address' => 'Rue des Écoles', 'city' => 'Pointe-Noire'],
            ['name' => 'Restaurant Le Baobab', 'email' => 'reservation@baobab.cg', 'phone' => '+242 06 890 12 34', 'address' => 'Avenue Foch', 'city' => 'Pointe-Noire'],
            ['name' => 'Bureau d\'études TECH-CONGO', 'email' => 'contact@techcongo.cg', 'phone' => '+242 06 145 78 23', 'address' => 'Immeuble Poto-Poto', 'city' => 'Brazzaville'],
        ];

        $clientModels = [];
        foreach ($clients as $c) {
            $clientModels[] = Client::create($c);
        }

        $productList = Product::all();

        foreach ($productList->take(5) as $index => $product) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'type' => 'entree',
                'quantity' => 10 + $index * 5,
                'reason' => 'Réapprovisionnement initial',
            ]);
        }

        foreach ($productList->skip(5)->take(3) as $index => $product) {
            if ($product->stock_quantity > 5) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'type' => 'sortie',
                    'quantity' => 3 + $index,
                    'reason' => 'Vente client',
                ]);
            }
        }

        $quoteData = [
            ['client' => 0, 'status' => 'accepte', 'items' => [[0, 2], [2, 3]]],
            ['client' => 1, 'status' => 'envoye', 'items' => [[1, 1], [4, 1]]],
            ['client' => 2, 'status' => 'brouillon', 'items' => [[3, 10], [8, 20]]],
            ['client' => 3, 'status' => 'accepte', 'items' => [[7, 5], [8, 30]]],
            ['client' => 4, 'status' => 'refuse', 'items' => [[9, 2]]],
            ['client' => 5, 'status' => 'envoye', 'items' => [[0, 1], [5, 2], [6, 1]]],
        ];

        foreach ($quoteData as $index => $data) {
            $reference = 'DEV-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $total = 0;
            $items = [];

            foreach ($data['items'] as [$productIdx, $qty]) {
                $product = $productList[$productIdx];
                $lineTotal = $product->price * $qty;
                $total += $lineTotal;
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ];
            }

            $quote = Quote::create([
                'client_id' => $clientModels[$data['client']]->id,
                'user_id' => $admin->id,
                'reference' => $reference,
                'total_amount' => $total,
                'status' => $data['status'],
                'created_at' => now()->subDays(15 - $index * 2),
            ]);

            foreach ($items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            if ($data['status'] === 'accepte') {
                $invoiceRef = 'FAC-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
                $invoice = Invoice::create([
                    'client_id' => $quote->client_id,
                    'quote_id' => $quote->id,
                    'user_id' => $admin->id,
                    'reference' => $invoiceRef,
                    'total_amount' => $total,
                    'status' => $index === 0 ? 'payee' : 'impayee',
                    'created_at' => now()->subDays(10 - $index * 2),
                ]);

                foreach ($items as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }
            }
        }

        $this->command->info('Donnees de demonstration inserees.');
    }
}