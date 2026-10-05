<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use App\Models\Category;
use App\Models\Supplier;
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
        if (!$admin) return;

        // Catégories
        $categories = [
            ['name' => 'Informatique'],
            ['name' => 'Bureautique'],
            ['name' => 'Consommables'],
            ['name' => 'Accessoires'],
        ];
        $catModels = [];
        foreach ($categories as $c) {
            $catModels[] = Category::create($c);
        }

        // Fournisseurs
        $suppliers = [
            ['name' => 'TechDistrib Congo', 'email' => 'contact@techdistrib.cg', 'phone' => '+242 06 111 22 33', 'city' => 'Brazzaville'],
            ['name' => 'BuroPlus', 'email' => 'contact@buroplus.cg', 'phone' => '+242 05 222 33 44', 'city' => 'Pointe-Noire'],
            ['name' => 'Import Export SARL', 'email' => 'info@importexport.cg', 'phone' => '+242 06 333 44 55', 'city' => 'Brazzaville'],
        ];
        $supModels = [];
        foreach ($suppliers as $s) {
            $supModels[] = Supplier::create($s);
        }

        // Produits
        $products = [
            ['name' => 'Ordinateur portable HP 15', 'sku' => 'HP-15-001', 'barcode' => '3456789012345', 'description' => 'Core i5, 8 Go RAM, 512 Go SSD', 'price' => 385000, 'purchase_price' => 310000, 'stock_quantity' => 12, 'stock_alert' => 5, 'category_id' => $catModels[0]->id, 'supplier_id' => $supModels[0]->id],
            ['name' => 'Imprimante Canon LBP223', 'sku' => 'CAN-LBP223', 'barcode' => '3456789012346', 'description' => 'Laser monochrome', 'price' => 185000, 'purchase_price' => 150000, 'stock_quantity' => 8, 'stock_alert' => 3, 'category_id' => $catModels[1]->id, 'supplier_id' => $supModels[1]->id],
            ['name' => 'Clavier sans fil Logitech', 'sku' => 'LOG-K400', 'barcode' => '3456789012347', 'description' => 'Clavier AZERTY Bluetooth', 'price' => 25000, 'purchase_price' => 18000, 'stock_quantity' => 45, 'stock_alert' => 10, 'category_id' => $catModels[3]->id, 'supplier_id' => $supModels[0]->id],
            ['name' => 'Souris optique Logitech M90', 'sku' => 'LOG-M90', 'barcode' => '3456789012348', 'description' => 'Souris USB 1000 dpi', 'price' => 7500, 'purchase_price' => 5000, 'stock_quantity' => 60, 'stock_alert' => 15, 'category_id' => $catModels[3]->id, 'supplier_id' => $supModels[0]->id],
            ['name' => 'Écran Dell 24 pouces', 'sku' => 'DELL-24-01', 'barcode' => '3456789012349', 'description' => 'Full HD 1920x1080', 'price' => 145000, 'purchase_price' => 115000, 'stock_quantity' => 3, 'stock_alert' => 5, 'category_id' => $catModels[0]->id, 'supplier_id' => $supModels[0]->id],
            ['name' => 'Onduleur APC 650VA', 'sku' => 'APC-650', 'barcode' => '3456789012350', 'description' => 'Protection secteur', 'price' => 65000, 'purchase_price' => 50000, 'stock_quantity' => 15, 'stock_alert' => 5, 'category_id' => $catModels[0]->id, 'supplier_id' => $supModels[2]->id],
            ['name' => 'Disque dur externe 1To', 'sku' => 'HDD-EXT-1T', 'barcode' => '3456789012351', 'description' => 'USB 3.0', 'price' => 55000, 'purchase_price' => 42000, 'stock_quantity' => 22, 'stock_alert' => 8, 'category_id' => $catModels[0]->id, 'supplier_id' => $supModels[0]->id],
            ['name' => 'Cartouche encre HP 305', 'sku' => 'HP-305-N', 'barcode' => '3456789012352', 'description' => 'Noir', 'price' => 18500, 'purchase_price' => 13000, 'stock_quantity' => 2, 'stock_alert' => 10, 'category_id' => $catModels[2]->id, 'supplier_id' => $supModels[1]->id],
            ['name' => 'Papier A4 ramette 500 feuilles', 'sku' => 'PAP-A4-500', 'barcode' => '3456789012353', 'description' => '80g/m²', 'price' => 4500, 'purchase_price' => 3200, 'stock_quantity' => 180, 'stock_alert' => 50, 'category_id' => $catModels[2]->id, 'supplier_id' => $supModels[1]->id],
            ['name' => 'Webcam Logitech C270', 'sku' => 'LOG-C270', 'barcode' => '3456789012354', 'description' => 'HD 720p', 'price' => 32000, 'purchase_price' => 24000, 'stock_quantity' => 0, 'stock_alert' => 5, 'category_id' => $catModels[3]->id, 'supplier_id' => $supModels[0]->id],
        ];
        foreach ($products as $p) {
            Product::create($p);
        }

        // Clients
        $clients = [
            ['name' => 'Entreprise Mbongo SARL', 'type' => 'entreprise', 'email' => 'contact@mbongo.cg', 'phone' => '+242 06 512 34 56', 'address' => '12 Avenue de la Paix', 'city' => 'Brazzaville', 'segment' => 'vip', 'credit_limit' => 5000000],
            ['name' => 'Cabinet Nkodia & Associés', 'type' => 'entreprise', 'email' => 'cabinet@nkodia.cg', 'phone' => '+242 05 478 92 10', 'address' => '45 Rue Mbochis', 'city' => 'Brazzaville', 'segment' => 'regulier', 'credit_limit' => 2000000],
            ['name' => 'Clinique Sainte-Marie', 'type' => 'entreprise', 'email' => 'info@clinique-sm.cg', 'phone' => '+242 06 723 45 89', 'address' => 'Boulevard Denis Sassou', 'city' => 'Brazzaville', 'segment' => 'vip', 'credit_limit' => 3000000],
            ['name' => 'École Les Palmiers', 'type' => 'entreprise', 'email' => 'direction@palmiers.cg', 'phone' => '+242 05 234 56 78', 'address' => 'Rue des Écoles', 'city' => 'Pointe-Noire', 'segment' => 'regulier', 'credit_limit' => 1500000],
            ['name' => 'Restaurant Le Baobab', 'type' => 'entreprise', 'email' => 'reservation@baobab.cg', 'phone' => '+242 06 890 12 34', 'address' => 'Avenue Foch', 'city' => 'Pointe-Noire', 'segment' => 'nouveau', 'credit_limit' => 500000],
            ['name' => 'Bureau d\'études TECH-CONGO', 'type' => 'entreprise', 'email' => 'contact@techcongo.cg', 'phone' => '+242 06 145 78 23', 'address' => 'Immeuble Poto-Poto', 'city' => 'Brazzaville', 'segment' => 'regulier', 'credit_limit' => 2500000],
        ];
        $clientModels = [];
        foreach ($clients as $c) {
            $clientModels[] = Client::create($c);
        }

        // Mouvements de stock
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

        // Devis
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
            $subtotal = 0;
            $items = [];

            foreach ($data['items'] as [$productIdx, $qty]) {
                $product = $productList[$productIdx];
                $lineSubtotal = $product->price * $qty;
                $subtotal += $lineSubtotal;
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'line_subtotal' => $lineSubtotal,
                ];
            }

            $taxAmount = round($subtotal * 0.18, 2);
            $total = $subtotal + $taxAmount;

            $quote = Quote::create([
                'client_id' => $clientModels[$data['client']]->id,
                'user_id' => $admin->id,
                'reference' => $reference,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'total_amount' => $total,
                'status' => $data['status'],
                'valid_until' => now()->addDays(30),
                'created_at' => now()->subDays(15 - $index * 2),
            ]);

            foreach ($items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_subtotal' => $item['line_subtotal'],
                    'tax_percent' => 18,
                ]);
            }

            if ($data['status'] === 'accepte') {
                $invoiceRef = 'FAC-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
                $invoice = Invoice::create([
                    'client_id' => $quote->client_id,
                    'quote_id' => $quote->id,
                    'user_id' => $admin->id,
                    'reference' => $invoiceRef,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => 0,
                    'total_amount' => $total,
                    'paid_amount' => $index === 0 ? $total : 0,
                    'status' => $index === 0 ? 'payee' : 'impayee',
                    'due_date' => now()->addDays(30),
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

        $this->command->info('Données de démonstration insérées.');
    }
}