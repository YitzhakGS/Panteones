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

        View::composer('beneficiarios.create', function ($view) {
            $view->with([
                'tiposDocumento' => cache()->remember('tipos_doc_beneficiario', 3600, fn() =>
                    TipoDocumento::whereHas('entidades', function ($q) {
                        $q->where('modelo', \App\Models\Beneficiario::class);
                    })->get()
                ),
                'titulares' => cache()->remember('titulares_vivos', 60, fn() =>
                    Titular::where('fallecido', 0)
                        ->orderBy('familia')
                        ->select('id_titular', 'familia') // solo lo que necesitas en el select
                        ->get()
                ),
            ]);
        });

        View::composer('titulares.create', function ($view) {
            $view->with('tiposDocumentoTitular',
                cache()->remember('tipos_doc_titular', 3600, fn() =>
                    TipoDocumento::whereHas('entidades', function ($q) {
                        $q->where('modelo', \App\Models\Titular::class);
                    })->get()
                )
            );
        });

        View::composer('lotes.create', function ($view) {
            $view->with([
                'secciones'      => cache()->remember('secciones', 3600, fn() =>
                    CatSeccion::orderBy('nombre')->get()
                ),
                'espaciosFisicos' => cache()->remember('espacios_fisicos', 3600, fn() =>
                    EspacioFisico::orderBy('nombre')->get()
                ),
            ]);
        });
      
    }
}
