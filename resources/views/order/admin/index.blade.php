@extends('templates.app')

@push('style')
    @section('content-dinamis')
        <div class="container mt-3">
            <div class="d-flex justify-content-end">
                <form class="d-flex me-3" action="{{ route('order.data') }}" method="GET">
                    <input type="date" name="date" placeholder="Cari Pembelian..." class="form-control me-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="?" class="btn btn-danger ms-2">Clear</a>
                </form>
                <a href="{{ route('order.export-excel') }}" class="btn btn-success">Export Data (excel)</a>
            </div>
            <table class="table table-stripped table-bordered mt-3 text-center">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Pembeli</th>
                        <th>Obat</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perpage() + ($index + 1) }}</td>
                            <td>{{ $order['name_customer'] }}</td>
                            <td>
                                <ol>
                                    @foreach ($order['medicines'] as $medicine)
                                        <li>
                                            {{ $medicine['name_medicine'] }} (Quantity: {{ $medicine['qty'] }}) : Rp. {{ number_format($medicine['sub_price'], 0, ',', '.') }}
                                        </li>
                                    @endforeach
                                </ol>
                            </td>
                            <td>{{ $order['user']['name'] }}</td>
                            @php
                                setLocale(LC_ALL, 'IND');
                            @endphp
                            {{-- carbon merupakan package untuk melakukan manipulasi waktu --}}
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->formatLocalized('%d %B %Y') }}</td>
                            {{-- <td>
                                <a href="{{ route('order.download', $order['id']) }}" class="btn btn-secondary">Unduh (.pdf)</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">{{ $orders->links() }}</div>
        </div>
    @endsection
    @push('script')
