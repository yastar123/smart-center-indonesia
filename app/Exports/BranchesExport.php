<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Branch::select('id','name','address','city','regency','phone','email','status','created_at')->get();
    }

    public function headings(): array
    {
        return ['ID','Name','Address','City','Regency','Phone','Email','Status','Created At'];
    }
}
