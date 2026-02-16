<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\rolController;
use Illuminate\Support\Facades\Route;



use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\SeccionesController;
use App\Models\CatSeccion;

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



Route::resource('secciones', SeccionesController::class)
    ->parameters([
        'secciones' => 'seccion'
    ]);

//Route::post('/edit','SeccionesController@update');

Route::get('/secciones/{seccion}/data', [SeccionesController::class, 'getData'])->name('secciones.data');

Route::get('/test-seccion/{seccion}', function (CatSeccion $seccion){
    return $seccion;
});

/*
| esta ultima funcion fue para verificar si podia obtener los datos de un elemento en la tabla seleccion, y lo hace de manera correcta
*/


