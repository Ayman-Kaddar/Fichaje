<?php

namespace App\Models;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithUpserts, WithHeadingRow
{
    public function model(array $row)
    {

        $role = Role::where("name", "Treballador")->first();

        $userExist = User::where('email', $row['correu'])->first();

        if ($userExist) {
            throw new Exception('Error: El correu ' . $row['correu'] . ' ja està registrat.');
        }

        return new User([
            'name' => $row['nom'],
            'email' => $row['correu'],
            'role_id' => $role->id,
            'company_id' => Auth::user()->company_id
        ]);
    }

    public function uniqueBy()
    {
        return 'email';
    }
}
