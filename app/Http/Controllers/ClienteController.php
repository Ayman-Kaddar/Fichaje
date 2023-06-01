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

        $total = 0;

        foreach ($fitxatges as $fitxatge) {
            $timestamp1 = strtotime($fitxatge->sortida ?? now());
            $timestamp2 = strtotime($fitxatge->entrada);

            $diferencia = ($timestamp1 - $timestamp2) / 60; // Diferencia en minutos

            // Obtiene los descansos para el fitxatge actual
            $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

            $tempsDescansat = 0;

            // Calcula el tiempo total de descanso para cada descanso
            foreach ($descansos as $descans) {
                $timestampcontinuitat = strtotime($descans->continuitat ?? now());
                $timestamppausa = strtotime($descans->pausa);

                $tempsDescansat += ($timestampcontinuitat - $timestamppausa) / 60; // Descanso en minutos
            }

            $total += $diferencia - $tempsDescansat;
        }

        // Convertir el total a horas y minutos
        $horas = floor($total / 60);
        $minutos = $total % 60;

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

        return view('cliente.inici', compact("horas", "minutos", "estat"));
    }
}
