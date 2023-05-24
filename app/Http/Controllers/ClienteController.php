<?php

namespace App\Http\Controllers;

use App\Models\Fitxatge;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function inici()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $ultim_fitxatge = Fitxatge::where('user_id', Auth::user()->id)->orderBy("id", "DESC")->first();

        if ($ultim_fitxatge && !$ultim_fitxatge->sortida) {
            $fitxatge = $ultim_fitxatge;
        } else {
            $fitxatge = null;
        }

        return view('cliente.inici', compact("fitxatge"));
    }
}
