<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MedicinesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Medicine::all();
    }

    public function headings(): array
    {
        return [
            'ID Obat',
            'Nama Obat',
            'Tipe',
            'Stock',
            'Harga'
        ];
    }

    public function map($medicine): array
    {
        return [
            $medicine->id,
            $medicine->name,
            $medicine->type,
            $medicine->stock,
            $medicine->price,
        ];
    }
}
