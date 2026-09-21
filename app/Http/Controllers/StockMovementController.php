<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('StockMovements/Index', [
            'movements' => $movements
        ]);
    }

    public function create()
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'stock_quantity']);
        return Inertia::render('StockMovements/Create', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entree,sortie',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            if ($validated['type'] === 'sortie' && $product->stock_quantity < $validated['quantity']) {
                abort(422, 'Stock insuffisant. Disponible : ' . $product->stock_quantity);
            }

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? null,
            ]);

            if ($validated['type'] === 'entree') {
                $product->increment('stock_quantity', $validated['quantity']);
            } else {
                $product->decrement('stock_quantity', $validated['quantity']);
            }
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Mouvement de stock enregistré avec succès.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        DB::transaction(function () use ($stockMovement) {
            $product = Product::lockForUpdate()->findOrFail($stockMovement->product_id);

            // Annuler l'effet du mouvement
            if ($stockMovement->type === 'entree') {
                if ($product->stock_quantity < $stockMovement->quantity) {
                    abort(422, 'Impossible d\'annuler : le stock deviendrait négatif.');
                }
                $product->decrement('stock_quantity', $stockMovement->quantity);
            } else {
                $product->increment('stock_quantity', $stockMovement->quantity);
            }

            $stockMovement->delete();
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Mouvement annulé avec succès.');
    }
}