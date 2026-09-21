<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['client', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get(['id', 'name', 'email']);
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'price', 'stock_quantity']);

        return Inertia::render('Quotes/Create', [
            'clients' => $clients,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:brouillon,envoye,accepte,refuse',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $reference = 'DEV-' . date('Y') . '-' . str_pad(Quote::count() + 1, 4, '0', STR_PAD_LEFT);

            $total = collect($validated['items'])->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $quote = Quote::create([
                'client_id' => $validated['client_id'],
                'user_id' => $request->user()->id,
                'reference' => $reference,
                'total_amount' => $total,
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('quotes.index')
            ->with('success', 'Devis créé avec succès.');
    }

    public function show(Quote $quote)
    {
        $quote->load(['client', 'user', 'items.product']);

        return Inertia::render('Quotes/Show', [
            'quote' => $quote
        ]);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Devis supprimé avec succès.');
    }

    public function convertToInvoice(Quote $quote)
    {
        if ($quote->invoice) {
            return redirect()->route('quotes.show', $quote->id)
                ->with('error', 'Ce devis a déjà été transformé en facture.');
        }

        DB::transaction(function () use ($quote) {
            $reference = 'FAC-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'client_id' => $quote->client_id,
                'quote_id' => $quote->id,
                'user_id' => $quote->user_id,
                'reference' => $reference,
                'total_amount' => $quote->total_amount,
                'status' => 'impayee',
            ]);

            foreach ($quote->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ]);
            }

            $quote->update(['status' => 'accepte']);
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Devis transformé en facture avec succès.');
    }

    public function downloadPdf(Quote $quote)
    {
        $quote->load(['client', 'user', 'items.product']);

        $pdf = Pdf::loadView('pdf.quote', ['quote' => $quote]);

        return $pdf->download('devis-' . $quote->reference . '.pdf');
    }
}