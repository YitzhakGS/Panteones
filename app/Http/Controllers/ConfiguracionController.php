<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    public function guardarConfiguracion(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'color-primario' => 'required',
            'color-secundario' => 'required',
            'color-base' => 'required',
            'color-complemento' => 'required',
            'colorFuenteB' => 'required',
            'colorFuenteN' => 'required',
        ]);
    
        // Si la validación falla, redirigir de vuelta al formulario con mensajes de error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Ruta del archivo de configuración
        $configFilePath = config_path('SCITS.php');
    
        // Leer el contenido del archivo
        $config = file_get_contents($configFilePath);
    
        // Reemplazar los valores en el contenido del archivo
        $config = preg_replace("/('color-primario' => ')(.*?)(')/", "$1" . $request->input('color-primario') . "$3", $config);
        $config = preg_replace("/('color-secundario' => ')(.*?)(')/", "$1" . $request->input('color-secundario') . "$3", $config);
        $config = preg_replace("/('color-base' => ')(.*?)(')/", "$1" . $request->input('color-base') . "$3", $config);
        $config = preg_replace("/('color-complemento' => ')(.*?)(')/", "$1" . $request->input('color-complemento') . "$3", $config);
        $config = preg_replace("/('colorFuenteB' => ')(.*?)(')/", "$1" . $request->input('colorFuenteB') . "$3", $config);
        $config = preg_replace("/('colorFuenteN' => ')(.*?)(')/", "$1" . $request->input('colorFuenteN') . "$3", $config);
    
        // Escribir el contenido actualizado de vuelta al archivo
        file_put_contents($configFilePath, $config);
    
        // Redirigir a la vista de configuración con un mensaje de éxito personalizado
        return redirect()->route('configuracion')->with('success', '¡Configuración guardada exitosamente!');
    }
}
