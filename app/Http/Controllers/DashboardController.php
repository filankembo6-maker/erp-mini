<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Client;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\StockMovement;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $lowStock = Product::whereColumn('stock_quantity', '<=', 'stock_alert')
            ->orderBy('stock_quantity')
            ->limit(6)
            ->get();

        $recentQuotes = Quote::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentInvoices = Invoice::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingQuotes = Quote::whereIn('status', ['brouillon', 'envoye']);
        $unpaidInvoices = Invoice::where('status', 'impayee');

        $lateInvoices = Invoice::where('status', 'impayee')
            ->where('created_at', '<=', Carbon::now()->subDays(30));

        $revenueMonth = Invoice::where('status', 'payee')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'stock_alert')->count();
        $lateInvoicesCount = $lateInvoices->count();

        // Notifications : produits en alerte + factures en retard
        $notifications = collect();

        if ($lowStockCount > 0) {
            $notifications->push([
                'type' => 'stock',
                'title' => $lowStockCount . ' produit(s) en alerte de stock',
                'description' => 'Certains produits sont sous le seuil de réapprovisionnement.',
                'href' => '/products',
            ]);
        }

        if ($lateInvoicesCount > 0) {
            $notifications->push([
                'type' => 'invoice',
                'title' => $lateInvoicesCount . ' facture(s) en retard',
                'description' => 'Des factures impayées dépassent 30 jours.',
                'href' => '/invoices',
            ]);
        }

        // Historique d'activité
        $activity = collect();

        StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->each(function ($m) use ($activity) {
                $activity->push([
                    'type' => $m->type === 'entree' ? 'create' : 'update',
                    'description' => ($m->type === 'entree' ? 'Entrée stock : ' : 'Sortie stock : ')
                        . ($m->product->name ?? '') . ' (' . $m->quantity . ')',
                    'user' => $m->user->name ?? 'Système',
                    'created_at' => $m->created_at,
                ]);
            });

        Quote::with(['client', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->each(function ($q) use ($activity) {
                $activity->push([
                    'type' => 'create',
                    'description' => 'Devis ' . $q->reference . ' — ' . ($q->client->name ?? ''),
                    'user' => $q->user->name ?? 'Système',
                    'created_at' => $q->created_at,
                ]);
            });

        Invoice::with(['client', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->each(function ($i) use ($activity) {
                $activity->push([
                    'type' => $i->status === 'payee' ? 'create' : 'update',
                    'description' => 'Facture ' . $i->reference . ' — ' . ($i->client->name ?? ''),
                    'user' => $i->user->name ?? 'Système',
                    'created_at' => $i->created_at,
                ]);
            });

        $activity = $activity->sortByDesc('created_at')->take(6)->values();

        return Inertia::render('Dashboard', [
            'stats' => [
                'products' => Product::count(),
                'clients' => Client::count(),
                'quotes_pending' => $pendingQuotes->count(),
                'quotes_pending_amount' => $pendingQuotes->sum('total_amount'),
                'invoices_unpaid' => $unpaidInvoices->count(),
                'invoices_unpaid_amount' => $unpaidInvoices->sum('total_amount'),
                'invoices_late' => $lateInvoicesCount,
                'revenue_month' => $revenueMonth,
                'low_stock_count' => $lowStockCount,
            ],
            'notifications' => $notifications,
            'low_stock' => $lowStock,
            'recent_quotes' => $recentQuotes,
            'recent_invoices' => $recentInvoices,
            'activity' => $activity,
        ]);
    }
}