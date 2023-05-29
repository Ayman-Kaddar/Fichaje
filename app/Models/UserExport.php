<?php

namespace App\Models;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class UserExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $role = Role::where("name", "Treballador")->first();

        $users = DB::table('Users')->where('company_id', Auth::user()->company_id)->where('role_id', $role->id)->select('name', 'email')->get();

        return collect($users);
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Correu',
        ];
    }
}
