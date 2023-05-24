<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GraficosController extends Controller
{
    public function graficos()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        return view('admin.graficos');
    }
}
