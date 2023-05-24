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
                $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

                $tempsDescansat = 0;

                foreach ($descansos as $descans) {
                    $timestamppausa = strtotime($descans->pausa);
                    $timestampcontinuitat = strtotime($descans->continuitat);

                    $tempsDescansat += ($timestampcontinuitat - $timestamppausa) / (60 * 60);
                }

                $fitxatge->entrada = Carbon::parse($fitxatge->entrada)->format('H:i:s');
                /* $fitxatge->pausa = $fitxatge->pausa ? Carbon::parse($fitxatge->pausa)->format('H:i:s') : "-";
                $fitxatge->continuitat = $fitxatge->continuitat ? Carbon::parse($fitxatge->continuitat)->format('H:i:s') : "-"; */
                $fitxatge->descans = floor($tempsDescansat) . 'h';

                $fitxatge->sortida = Carbon::parse($fitxatge->sortida)->format('H:i:s');
                $fitxatge->data = Carbon::parse($fitxatge->entrada)->format('d/m/Y');

                // calcular horas totales
                /* $entrada = Carbon::parse($fitxatge->entrada);
                $sortida = Carbon::parse($fitxatge->sortida); */

                $timestamp1 = strtotime($fitxatge->sortida);
                $timestamp2 = strtotime($fitxatge->entrada);

                $diferencia = (($timestamp1 - $timestamp2) / (60 * 60)) - $tempsDescansat;

                $fitxatge->hores_totals = floor($diferencia) . 'h';
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
