<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\MistralService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'user'])->orderBy('created_at', 'desc')->get();
        return Inertia::render('Invoices/Index', ['invoices' => $invoices]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'user', 'items.product', 'quote']);
        return Inertia::render('Invoices/Show', ['invoice' => $invoice]);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:impayee,payee,annulee',
        ]);
        $invoice->update(['status' => $validated['status']]);
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Statut mis à jour.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Facture supprimée.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'user', 'items.product', 'quote']);
        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);
        return $pdf->download('facture-' . $invoice->reference . '.pdf');
    }

    public function reminderDraft(Invoice $invoice, MistralService $mistral)
    {
        $invoice->load('client');

        $system = "Tu es un assistant commercial d'une entreprise. Tu rédiges des emails de relance professionnels, courtois et concis en français. Structure : objet, corps du message, formule de politesse. N'invente aucune information. N'utilise que les données fournies.";

        $user = "Rédige un email de relance pour la facture suivante.\n\n"
            . "Client : " . $invoice->client->name . "\n"
            . "Référence : " . $invoice->reference . "\n"
            . "Montant : " . number_format($invoice->total_amount, 2, ',', ' ') . " FCFA\n"
            . "Date d'émission : " . $invoice->created_at->format('d/m/Y') . "\n"
            . "Statut : impayée";

        $draft = $mistral->ask($system, $user);

        if (!$draft) {
            return response()->json([
                'error' => 'Le service de rédaction est indisponible. Réessayez dans quelques instants.',
            ], 503);
        }

        return response()->json(['draft' => $draft]);
    }
}