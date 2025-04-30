<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\OuvrageController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\LivreImageController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GiftListController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PaiementController;

// Routes du Front Office (accessibles aux internautes)
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/catalogue', [FrontController::class, 'catalogue'])->name('front.catalogue');
Route::get('/livre/{id}', [FrontController::class, 'detail'])->name('front.detail');
Route::get('/a-propos', [FrontController::class, 'about'])->name('front.about');
Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact');
Route::get('/recherche', [FrontController::class, 'recherche'])->name('front.recherche');

// Soumission d'un commentaire
Route::post('/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes du panier
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
Route::post('/panier/mettre-a-jour', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/panier/validation', [CartController::class, 'checkout'])->name('cart.checkout');

// Routes pour les listes de cadeaux
Route::get('/mes-listes', [GiftListController::class, 'index'])->name('gift-lists.index');
Route::get('/mes-listes/creation', [GiftListController::class, 'create'])->name('gift-lists.create');
Route::post('/mes-listes', [GiftListController::class, 'store'])->name('gift-lists.store');
Route::get('/mes-listes/{id}', [GiftListController::class, 'show'])->name('gift-lists.show');
Route::get('/mes-listes/{id}/modifier', [GiftListController::class, 'edit'])->name('gift-lists.edit');
Route::put('/mes-listes/{id}', [GiftListController::class, 'update'])->name('gift-lists.update');
Route::delete('/mes-listes/{id}', [GiftListController::class, 'destroy'])->name('gift-lists.destroy');
Route::post('/mes-listes/{id}/ajouter-ouvrage', [GiftListController::class, 'addItem'])->name('gift-lists.add-item');
Route::delete('/mes-listes/{listId}/retirer-ouvrage/{ouvrageId}', [GiftListController::class, 'removeItem'])->name('gift-lists.remove-item');
Route::post('/mes-listes/{id}/regenerer-code', [GiftListController::class, 'regenerateCode'])->name('gift-lists.regenerate-code');
Route::get('/liste-cadeaux/{code}', [GiftListController::class, 'showByCode'])->name('gift-lists.public');
Route::post('/liste-cadeaux/{code}/reserver/{ouvrageId}', [GiftListController::class, 'reserveItem'])->name('gift-lists.reserve-item');

// Routes pour les commandes
Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index');
Route::get('/mes-commandes/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/commandes', [OrderController::class, 'store'])->name('orders.store');
Route::post('/mes-commandes/{id}/annuler', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/mes-commandes/{id}/facture', [OrderController::class, 'invoice'])->name('orders.invoice');

// Paiement PayPal
Route::get('/commande/{order}/payer-paypal', [PaiementController::class, 'payerAvecPayPal'])->name('paiement.paypal');
Route::get('/paiement/{order}/success', [PaiementController::class, 'paiementSucces'])->name('paiement.success');
Route::get('/paiement/{order}/cancel', [PaiementController::class, 'paiementAnnule'])->name('paiement.cancel');

// Routes de test pour la gestion des utilisateurs (à supprimer en production)
Route::get('/test/users', [TestController::class, 'showUsers'])->name('test.users');
Route::get('/test/create-users', [TestController::class, 'createTestUsers'])->name('test.create-users');

// Route pour accéder au back office
Route::get('/admin', function () {
    return view('layout');
})->name('admin.dashboard')->middleware(['auth', 'role:admin,manager,editor']);

// Groupe de routes pour le back office avec middleware d'authentification
Route::middleware(['auth'])->group(function () {
    // Routes accessibles aux éditeurs (descriptions, catégories, commentaires)
    Route::middleware(['role:editor,admin,manager'])->group(function () {
        Route::resource('categories', CategorieController::class)->parameters([
            'categories' => 'categorie'
        ]);
        Route::resource('ouvrages', OuvrageController::class)->only(['edit', 'update', 'show']);
        Route::get('/commentaires', [CommentaireController::class, 'index'])->name('commentaires.index');
        Route::put('/commentaires/{id}/valider', [CommentaireController::class, 'valider'])->name('commentaires.valider');
        Route::delete('/commentaires/{id}', [CommentaireController::class, 'supprimer'])->name('commentaires.supprimer');
    });
    
    // Routes accessibles aux gestionnaires (catalogue complet, stock, ventes)
    Route::middleware(['role:manager,admin'])->group(function () {
        Route::resource('ouvrages', OuvrageController::class)->except(['edit', 'update', 'show']);
        Route::resource('stocks', StockController::class);
        Route::post('/stocks/{stock}/ajouter', [StockController::class, 'ajouter'])->name('stocks.ajouter');
        Route::post('/stocks/{stock}/retirer', [StockController::class, 'retirer'])->name('stocks.retirer');
        Route::resource('ventes', VenteController::class);
        Route::resource('images', LivreImageController::class)->parameters(['images' => 'image']);
    });
    
    // Routes accessibles uniquement aux administrateurs (gestion utilisateurs, maintenance)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UtilisateurController::class);
        Route::resource('clients', ClientController::class);
        Route::patch('/clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
    });
});
