<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithValidation, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        return $rows;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'employee_code' => 'required',
            'designation' => 'required',
            'department' => 'required',
            'state' => 'required',
            'city' => 'required',
            'mobile_no' => 'required',
            'role' => 'required',
        ];
    }
}
