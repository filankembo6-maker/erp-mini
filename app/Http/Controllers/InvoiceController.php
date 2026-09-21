<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices
        ]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'user', 'items.product', 'quote']);

        return Inertia::render('Invoices/Show', [
            'invoice' => $invoice
        ]);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:impayee,payee,annulee',
        ]);

        $invoice->update(['status' => $validated['status']]);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Statut de la facture mis à jour.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'user', 'items.product', 'quote']);

        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);

        return $pdf->download('facture-' . $invoice->reference . '.pdf');
    }
}