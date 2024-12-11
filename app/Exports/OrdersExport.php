<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::with('user')->orderBy('name_customer', 'ASC')->get();
    }

    public function headings(): array
    {
        return [
            'ID Pembeli',
            'Nama Kasir',
            'Daftar Obat',
            'Total Bayar',
            'Nama Pembeli',
            'Tanggal'
        ];
    }

    public function map($order): array
    {
        $dataobat = '';
        foreach ($order->medicines as $key => $value) {
            $format = $key+1 . ". ". $value['name_medicine'] . " (" . $value['qty'] . "pcs) - Rp " . number_format($value['sub_price'], 0, ',', '.') . "),";
            $dataobat .= $format;
        }
        return [
            $order->id,
            $order->user->name,
            $dataobat,
            "Rp. ".number_format($order->total_price, 0,',','.'),
            $order->name_customer,
            \Carbon\Carbon::create($order->created_at)->locale('id')->translatedFormat('d F, Y')
        ];
    }
}
