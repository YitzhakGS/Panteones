<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\rolController;
use Illuminate\Support\Facades\Route;



use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\SeccionesController;
use App\Http\Controllers\CuadrillasController;
use App\Http\Controllers\EspaciosFisicosController;
use App\Http\Controllers\TitularesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/', function () {
    return view('welcome'); // Esto cargará la vista 'resources/views/inicio.blade.php'
})->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();
   //rutas de configuracion de color
   Route::get('/configuracion', function () {
    return view('configuracion');
})->name('configuracion');;
Route::post('/Gconfiguracion', [App\Http\Controllers\ConfiguracionController::class, 'guardarConfiguracion'])->name('guardarConfiguracion');
Route::resource('users', UserController::class);
Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::resource('rol', rolController::class);


// Rutas para el catálogo de secciones
Route::resource('secciones', SeccionesController::class)
    ->parameters([
        'secciones' => 'seccion'
    ]);
Route::get('/secciones/{seccion}/data', 
    [SeccionesController::class, 'getData']
)->name('secciones.data');

// Rutas para el catálogo de cuadrillas
Route::resource('cuadrillas', CuadrillasController::class)
    ->parameters([
        'cuadrillas' => 'cuadrilla'
    ]);

Route::get('/cuadrillas/{cuadrilla}/data',
    [CuadrillasController::class, 'getData']
)->name('cuadrillas.data');


// Rutas para el catálogo de espacios físicos
Route::resource('espacios_fisicos', EspaciosFisicosController::class)
    ->parameters([
        'espacios_fisicos' => 'espacio_fisico'
    ]);

Route::get('/espacios_fisicos/{espacio_fisico}/data',
    [EspaciosFisicosController::class, 'getData']
)->name('espacios_fisicos.data');


// Rutas para el catálogo de titulares
Route::resource('titulares', TitularesController::class)
    ->parameters([
        'titulares' => 'titular'
    ]);

Route::get('titulares/{titular}/data', 
    [TitularesController::class, 'getData']
)->name('titulares.data');

