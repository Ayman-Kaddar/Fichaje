<?php

namespace App\Http\Controllers;

use App\Models\Descans;
use App\Models\Fitxatge;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class FitxadminController extends Controller
{

    public function fitxadmin(Request $request)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        $user_name = "";

        if ($request->user_filter) {
            $fitxatges = Fitxatge::orderBy('entrada', 'asc')->where("user_id", $request->user_filter)->get();

            foreach ($fitxatges as $fitxatge) {

                $fitxatge->data = Carbon::parse($fitxatge->entrada)->format('d/m/Y');

                $timestamp1 = strtotime($fitxatge->sortida ?? now());
                $timestamp2 = strtotime($fitxatge->entrada);

                $diferencia = ($timestamp1 - $timestamp2) / 60; // Diferencia en minutos

                // Obtiene los descansos para el fitxatge actual
                $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

                $tempsDescansat = 0;
                $total = 0;

                // Calcula el tiempo total de descanso para cada descanso
                foreach ($descansos as $descans) {
                    $timestampcontinuitat = strtotime($descans->continuitat ?? now());
                    $timestamppausa = strtotime($descans->pausa);

                    $tempsDescansat += ($timestampcontinuitat - $timestamppausa) / 60; // Descanso en minutos
                }

                $fitxatge->descans = floor($tempsDescansat / 60) . 'h ' . $tempsDescansat % 60 . 'm';

                $total += $diferencia - $tempsDescansat;
                // Convertir el total a horas y minutos
                $horas = floor($total / 60);
                $minutos = $total % 60;

                $fitxatge->entrada = Carbon::parse($fitxatge->entrada)->format('H:i:s');
                $sortida = Carbon::parse($fitxatge->sortida)->format('H:i:s');

                $fitxatge->sortida = $fitxatge->sortida ? Carbon::parse($fitxatge->sortida)->format('H:i:s') : null;

                $fitxatge->hores_totals = $horas . 'h ' . $minutos . 'm';

                if (!$fitxatge->sortida) {
                    $fitxatge->descans = '-';
                    $fitxatge->sortida = '-';
                    $fitxatge->hores_totals = '-';
                }
            }

            $user_selected = User::where("id", $request->user_filter)->first();

            $user_name = "$user_selected->name - $user_selected->email";
        } else {
            $fitxatges = null;
        }

        $role = Role::where("name", "Treballador")->first();
        $users = User::where("role_id", $role->id)->where("company_id", Auth::user()->company_id)->get();

        User::where("role_id", $role->id)->get();

        return view('admin.fitxadmin', compact('fitxatges', 'users', 'user_name'));
    }
}
