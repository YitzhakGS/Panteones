<?php

namespace App\Http\Controllers;
use App\Models\rol;
use Illuminate\Http\Request;

class rolController extends Controller
{
    public function index()
    {
        $roles = rol::all();
        return view('adminmenu.admincatalogos.Rol.index', compact('roles'));
    }

    public function create()
    {
        return view('adminmenu.admincatalogos.Rol.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        rol::create($request->all());
        return back();
    }

    public function edit(rol $rol)
    {
        return response()->json($rol);
    }

    public function update(Request $request, rol $rol)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $rol->update($request->all());
        return back();
    }

    public function destroy(rol $rol)
    {
        $rol->delete();
        return back();
    }
}
