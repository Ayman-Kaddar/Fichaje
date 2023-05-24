<?php

namespace App\Models;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\Auth;

class UserExport implements FromCollection
{
    public function collection()
    {

        $role = Role::where("name", "Treballador")->first();

        $users = User::where("role_id", $role->id)->where("company_id", Auth::user()->company_id)->get(["name", "email"]);

        return $users;
    }
}
