<?php

namespace App\Http\Controllers;

use App\Models\Descans;
use App\Models\Role;
use App\Models\User;
use App\Models\UserImport;
use App\Models\UserExport;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function importUsers(Request $request)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            try {
                Excel::import(new UserImport, $file);
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'El fitxer es incorrecte.');
            }


            return redirect()->back()->with('success', 'Els nous empleats han estat importats correctament.');
        } else {
            return redirect()->back()->with('error', 'No ha seleccionat cap fitxer per pujar.');
        }
    }

    public function exportUsers()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        $date = now()->format('YmdHos');
        $fileName = 'empleats_' . $date . '.xls';

        return Excel::download(new UserExport, $fileName);
    }

    public function destroy(User $user)
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        $events = $user->events;
        $fitxatges = $user->fitxatges;

        if ($fitxatges) {
            foreach ($fitxatges as $fitxatge) {
                $descansos = Descans::where('fixtage_id', $fitxatge->id)->get();

                if ($descansos) {
                    foreach ($descansos as $descans) {
                        $descans->delete();
                    }
                }

                $fitxatge->delete();
            }
        }

        if ($events) {
            foreach ($events as $event) {
                $event->delete();
            }
        }

        $user->delete();

        return redirect()->back()->with('success', 'Empleats ha estat eliminat correctament.');
    }
}
