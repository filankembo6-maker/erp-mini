<?php

namespace App\Http\Middleware;

use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->getRoleNames()->toArray(),
                ] : null,
            ],
            'notifications' => function () {
                $notifications = collect();

                $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'stock_alert')->count();
                if ($lowStockCount > 0) {
                    $notifications->push([
                        'type' => 'stock',
                        'title' => $lowStockCount . ' produit(s) en alerte de stock',
                        'description' => 'Certains produits sont sous le seuil de réapprovisionnement.',
                        'href' => '/products',
                    ]);
                }

                $lateInvoicesCount = Invoice::where('status', 'impayee')
                    ->where('created_at', '<=', Carbon::now()->subDays(30))
                    ->count();
                if ($lateInvoicesCount > 0) {
                    $notifications->push([
                        'type' => 'invoice',
                        'title' => $lateInvoicesCount . ' facture(s) en retard',
                        'description' => 'Des factures impayées dépassent 30 jours.',
                        'href' => '/invoices',
                    ]);
                }

                return $notifications->toArray();
            },
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}