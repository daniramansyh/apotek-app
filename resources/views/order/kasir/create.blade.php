@extends('templates.app')

@section('content-dinamis')
    <div class="container mt-3">
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @php
                $valueFormBefore = Session::get('valueFormBefore');
            @endphp
        @endif
        <form action="{{ route('kasir.order.tambah.proses') }}" class="card m-auto p-5" method="POST">
            @csrf
            {{-- Validasi error message --}}
            @if ($errors->any())
                <ul class="alert alert-danger p-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <p>Penanggung Jawab : <b>{{ Auth::user()->name }}</b></p>
            <div class="mb-3 row">
                <label for="name_customer" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name_customer" name="name_customer"
                        value="{{ isset($valueFormBefore) ? $valueFormBefore['name_customer'] : '' }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="medicines" class="col-sm-2 col-form-label">Obat</label>
                <div class="col-sm-10">
                    @if (isset($valueFormBefore))
                        @foreach ($valueFormBefore['medicines'] as $key => $medicine)
                            <div class="d-flex" id="medicines-{{$key}}">
                                <select name="medicines[]" class="form-select mb-2">
                                    @foreach ($medicines as $item)
                                        {{-- ternary, jika yg sedang di looping itu sama dengan item id obatnya, obat itu di select --}}
                                        <option value="{{ $item['id'] }}"
                                            {{ $medicine == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                                @if($key > 0)
                                <div class="m-2">
                                    <span style="cursor: pointer" onclick="deleteSelect('medicines-{{ $key }}')">X</span>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <select name="medicines[]" id="medicines-1" class="form-select mb-2">
                            <option selected hidden disabled>Pesanan 1</option>
                            @foreach ($medicines as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    @endif
                    <div id="wrap-medicines"></div>
                    <br>
                    <p style="cursor: pointer" class="text-primary" id="add-select">+ tambah obat</p>
                </div>
            </div>
            <button type="submit" class="btn btn-block btn-lg btn-primary">Konfirmasi Pembelian</button>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        let no = {{ isset($valueFormBefore) ? count($valueFormBefore['medicines']) + 1 : 2 }};
        // ketika tag dengan id add-select di click jalankan func berikut
        $("#add-select").on("click", function() {
            // tag html yg akan ditambahkan/dimunculkan
            let el = `<div id="medicines-${no}" class="d-flex mb-2">
                            <br>
                            <select name="medicines[]" id="medicines" class="form-select">
                                <option selected hidden disabled>Pesanan ${no}</option>
                                @foreach ($medicines as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            <span class="m-2" style="cursor: pointer" onclick="deleteSelect('medicines-${no}')">X</span>
                        </div>`;
            // append : tambahkan element html dibagian sblm penutup tag terkait (sblm penutup tag yg id nya wrap-medicines)
            $("#wrap-medicines").append(el);
            // increments variable no agar angka yg muncul di option selalu bertambah 1 sesuai jumlah selectnya
            no++;
        });

        function deleteSelect(elementId){
            $(`#${elementId}`).remove();
            no--;
        };
    </script>
@endpush
