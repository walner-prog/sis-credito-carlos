<?php

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarteraController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\AbonoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ConfiguracionController;
 

use App\Livewire\CreatePost;
 
 
Route::get('/posts/create', CreatePost::class);
 


 

// Página de login (solo invitados pueden verla)
Route::get('/login', function () {
    return view('welcome'); // tu vista de login
})->middleware('guest')->name('login');

// Redirigir la raíz "/" según el estado de autenticación
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Agrupamos todas las rutas protegidas
Route::middleware('auth')->group(function () {
    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes');

    // Carteras
    Route::get('/carteras', [CarteraController::class, 'index'])->name('carteras');

    // Créditos
    Route::get('/creditos', [CreditoController::class, 'index'])->name('creditos');

    // Abonos
    Route::get('/abonos', [AbonoController::class, 'index'])->name('abonos');
    Route::get('/abonos/report', [AbonoController::class, 'report'])->name('abonos.report');
    Route::get('/abonos/crear', [AbonoController::class, 'crear'])->name('abonos.crear');

    // Solo administradores
    Route::middleware('role:Administrador')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
        Route::get('/roles', [RolController::class, 'index'])->name('roles');
        Route::get('/configuraciones', [ConfiguracionController::class, 'index'])->name('configuraciones.index');


        // Perfil
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
