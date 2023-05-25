<?php

namespace App\Http\Controllers;

use App\Models\Descans;
use App\Models\Fitxatge;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function inici()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $hores = 0;
        $estat = "";

        $fitxatges = Fitxatge::where('user_id', Auth::user()->id)->where("entrada", "LIKE", now()->format('Y-m-d') . "%")->get();
        $ultim_fitxatge = Fitxatge::where('user_id', Auth::user()->id)->where("entrada", "LIKE", now()->format('Y-m-d') . "%")->orderBy("id", "DESC")->first();

        foreach ($fitxatges as $fitxatge) {
            $timestamp1 = strtotime($fitxatge->sortida ?? now());
            $timestamp2 = strtotime($fitxatge->entrada);

            $diferencia = ($timestamp1 - $timestamp2) / (60 * 60);

            $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

            $tempsDescansat = 0;

            foreach ($descansos as $descans) {
                $timestamppausa = strtotime($descans->pausa);
                $timestampcontinuitat = strtotime($descans->continuitat ?? now());

                $tempsDescansat += ($timestampcontinuitat - $timestamppausa) / (60 * 60);
            }


            $hores += $diferencia - $tempsDescansat;
            $hores = floor($hores);
        }

        if ($ultim_fitxatge) {

            $descans = Descans::where('fixtage_id', $ultim_fitxatge->id)->orderBy("id", "DESC")->first();

            if ($ultim_fitxatge->sortida) {
                $estat = "Comença la jornada";
            }

            if ($ultim_fitxatge->entrada && !$ultim_fitxatge->sortida) {
                $estat = "Jornada començada";
            }

            if ($descans) {
                if ($descans->pausa && !$descans->continuitat) {
                    $estat = "En un descans";
                }
            }
        } else {
            $hores = 0;
            $estat = "Comença la jornada";
        }

        return view('cliente.inici', compact("hores", "estat"));
    }
}
