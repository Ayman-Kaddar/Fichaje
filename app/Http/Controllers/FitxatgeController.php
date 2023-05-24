<?php

namespace App\Http\Controllers;

use App\Models\Descans;
use App\Models\Fitxatge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FitxatgeController extends Controller
{
    public function entrada()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        /* $fitxatge = Fitxatge::where('user_id', Auth::user()->id)
            ->whereDate('entrada', now()->toDateString())
            ->first(); */

        $fitxatge = Fitxatge::where('user_id', Auth::user()->id)
            ->whereDate('entrada', now()->toDateString())
            ->orderBy("id", "DESC")
            ->first();

        if ($fitxatge->id && !$fitxatge->sortida) {
            return redirect()->route('inici')->with('error', 'Ja has fitxat surt abans de tornar a fitxar');
        }

        $fitxatge = new Fitxatge();
        $fitxatge->user_id = Auth::user()->id;
        $fitxatge->entrada = now();
        $fitxatge->save();

        return redirect()->route('inici')->with('success', 'Has iniciat la teva jornada laboral')->with('alert-type', 'success');
    }

    public function pausa()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $fitxatge = Fitxatge::where('user_id', Auth::user()->id)
            ->whereDate('entrada', now()->toDateString())
            ->orderBy("id", "DESC")
            ->first();

        if (!$fitxatge || $fitxatge->sortida) {
            return redirect()->route('inici')->with('error', 'No has iniciat la jornada laboral avui');
        }

        $descans = Descans::where('fixtage_id', $fitxatge->id)->orderBy("id", "DESC")->first();

        if ($descans && !$descans->continuitat) {
            return redirect()->route('inici')->with('error', 'Ja has pausat.');
        }

        $descans = Descans::create([
            'pausa' => now(),
            'fixtage_id' => $fitxatge->id,
        ]);

        return redirect()->route('inici')->with('success', 'Has iniciat una pausa')->with('alert-type', 'success');
    }

    public function continuacio()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $fitxatge = Fitxatge::where('user_id', Auth::user()->id)
            ->whereDate('entrada', now()->toDateString())
            ->orderBy("id", "DESC")
            ->first();

        if (!$fitxatge || $fitxatge->sortida) {
            return redirect()->route('inici')->with('error', 'No has iniciat la jornada laboral avui');
        }

        $descans = Descans::where('fixtage_id', $fitxatge->id)->orderBy("id", "DESC")->first();

        if ($descans->id == null || $descans->continuitat != null) {
            return redirect()->route('inici')->with('error', "Heu d\' iniciar una pausa abans de continuar");
        }

        $descans->continuitat = now();
        $descans->update();

        return redirect()->route('inici')->with('success', 'Has reprès la teva activitat laboral')->with('alert-type', 'success');
    }

    public function sortida()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $date = now()->format('Y-m-d');
        $fitxatge = Fitxatge::where('user_id', Auth::user()->id)->whereDate('entrada', $date)->orderBy("id", "DESC")->first();
        $descans = Descans::where('fixtage_id', $fitxatge->id)->orderBy("id", "DESC")->first();

        if ($fitxatge) {

            if ($descans && $descans->continuitat == null) {
                return redirect()->route('inici')->with('error', "Esteu en una pausa.");
            }

            if (!$fitxatge->sortida) {
                $fitxatge->sortida = now();
                $fitxatge->update();

                $title = 'Has finalitzat la teva jornada laboral';
                $text = 'Gràcies per la teva feina avui!';
                $type = 'success';

                return redirect()->route('inici')->with('showSweetAlert', compact('title', 'text', 'type'));
            } else {
                $title = 'Ja has marcat la sortida avui';
                $text = 'No pots marcar la sortida dues vegades!';
                $type = 'error';

                return redirect()->route('inici')->with('showSweetAlert', compact('title', 'text', 'type'));
            }
        } else {
            $title = 'Encara no has marcat l\'entrada avui';
            $text = 'Has d\'iniciar la teva jornada laboral abans de marcar la sortida!';
            $type = 'error';

            return redirect()->route('inici')->with('showSweetAlert', compact('title', 'text', 'type'));
        }
    }

    public function devolverfitxarge($fecha)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Treballador")) {
            return redirect()->route("fitxadmin");
        }

        $fitxatges = Fitxatge::where('user_id', Auth::user()->id)->where("entrada", "LIKE", "$fecha%")->whereNotNull("sortida")->get();
        if ($fitxatges->isEmpty()) {
            return response()->json([
                "fecha" => $fecha,
                "total" => 0,
                "entrada" => "No has acabat la jornada",
                "sortida" => "No has acabat la jornada",
            ]);
        }

        $total = 0;

        foreach ($fitxatges as $fitxatge) {
            $timestamp1 = strtotime($fitxatge->sortida);
            $timestamp2 = strtotime($fitxatge->entrada);

            $diferencia = ($timestamp1 - $timestamp2) / (60 * 60);

            $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

            $tempsDescansat = 0;

            foreach ($descansos as $descans) {
                $timestamppausa = strtotime($descans->pausa);
                $timestampcontinuitat = strtotime($descans->continuitat);

                $tempsDescansat += ($timestampcontinuitat - $timestamppausa) / (60 * 60);
            }

            $total += $diferencia - $tempsDescansat;
        }

        return response()->json([
            "fecha" => $fecha,
            "total" => floor($total),
            "entrada" => Carbon::createFromFormat('Y-m-d H:i:s', $fitxatges[0]->entrada)->format('d/m/Y H:i:s'),
            "sortida" => Carbon::createFromFormat('Y-m-d H:i:s', $fitxatges[count($fitxatges) - 1]->sortida)->format('d/m/Y H:i:s'),
        ]);
    }
}
