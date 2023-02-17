<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromArray, WithHeadings
{
    protected $error;

    function __construct($error)
    {
        $this->error = $error;
    }

    public function array(): array // this was query() before
    {
        return $this->error;
    }


    public function headings(): array
    {
        return [
            'first_name',
            'email',
            'employee_code',
            'error',
        ];
    }
}
