<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Produits (admin + magasinier)
    Route::middleware('role:admin,magasinier')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Clients (admin + commercial)
    Route::middleware('role:admin,commercial')->group(function () {
        Route::resource('clients', ClientController::class);
    });

    // Stocks (admin + magasinier)
    Route::middleware('role:admin,magasinier')->group(function () {
        Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
        Route::get('stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
        Route::post('stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
        Route::delete('stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])->name('stock-movements.destroy');
    });

    // Devis (admin + commercial)
    Route::middleware('role:admin,commercial')->group(function () {
        Route::get('quotes', [QuoteController::class, 'index'])->name('quotes.index');
        Route::get('quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
        Route::post('quotes', [QuoteController::class, 'store'])->name('quotes.store');
        Route::get('quotes/{quote}/pdf', [QuoteController::class, 'downloadPdf'])->name('quotes.pdf');
        Route::get('quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
        Route::delete('quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
        Route::post('quotes/{quote}/convert', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert');
    });

    // Factures (admin + commercial)
    Route::middleware('role:admin,commercial')->group(function () {
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    });
});

require __DIR__.'/auth.php';
