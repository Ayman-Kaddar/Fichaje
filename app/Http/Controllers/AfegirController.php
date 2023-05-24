<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AfegirController extends Controller
{
    public function afegir()
    {

        if (!(Auth::user()->role && Auth::user()->role->name == "Administrador")) {
            return redirect()->route("inici");
        }

        $role = Role::where("name", "Treballador")->first();

        $users = User::where("role_id", $role->id)->where("company_id", Auth::user()->company_id)->get();

        return view('admin.afegir', ['users' => $users]);
    }
}
