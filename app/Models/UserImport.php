<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel, WithUpserts
{
    public function model(array $row)
    {

        $role = Role::where("name", "Treballador")->first();

        return new User([
            'name' => $row[0],
            'email' => $row[1],
            'role_id' => $role->id,
            'company_id' => Auth::user()->company_id
        ]);
    }

    public function uniqueBy()
    {
        return 'email';
    }
}
