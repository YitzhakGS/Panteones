<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\TipoDocumento;
use App\Models\CatSeccion;
use App\Models\EspacioFisico;
use App\Models\Titular;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('titulares.create', function ($view) {
            $view->with('tiposDocumentoTitular',
                TipoDocumento::whereHas('entidades', function ($q) {
                    $q->where('modelo', \App\Models\Titular::class);
                })->get()
            );
        });

        View::composer('lotes.create', function ($view) {
            $view->with([
                'secciones' => CatSeccion::orderBy('nombre')->get(),
                'espaciosFisicos' => EspacioFisico::orderBy('nombre')->get()
            ]);
        });

        View::composer('beneficiarios.create', function ($view) {
            $view->with([
                'tiposDocumento' => TipoDocumento::whereHas('entidades', function ($q) {
                    $q->where('modelo', \App\Models\Beneficiario::class);
                })->get(),

                'titulares' => Titular::where('fallecido', 0)
                    ->orderBy('familia')
                    ->get()
            ]);
        });
      
    }
}
