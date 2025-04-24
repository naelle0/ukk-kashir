<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
    public function headings(): array
    {
        return [
            'no',
            'nama',
            'email',
            'role',
        ];
    }
    private static $counter = 1;
    public function map($user): array
    {
        $id = self::$counter++;
        return [
            $id,
            $user->name,
            $user->email,
            $user->role,
        ];
    }
}
