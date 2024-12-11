<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function data() {
        $orders = Order::with('user')->where('created_at', '!=', null)->when(request('date'), function ($query) { return $query->whereDate('created_at', request('date')); })->simplePaginate(10);
        return view('order.admin.index', compact('orders'));
    }

    public function exportExcel() {
        return Excel::download(new OrdersExport, 'data_pembelian.xlsx');
    }

    public function index()
    {
        $orders = Order::with('user')->where('user_id', Auth::user()->id)->when(request('date'), function ($query) { return $query->whereDate('created_at', request('date')); })->simplePaginate(10);
        // $orders = Order::where('user_id', Auth::user()->id)->simplePaginate(10);
        return view('order.kasir.kasir', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);
        
        // mencari jumlah item yang sama pada array, strukturnya :
        // [ "item" => "jumlah" ]
        $arrayDistinct = array_count_values($request->medicines);
        // menyiapkan array kosong untuk menampung format array baru
        $arrayAssocMedicines = [];
    
        // looping hasil penghitungan item distinct (duplikat)
        // key akan berupa value dr input medicines (id), item array berupa jumlah penghitungan item duplikat
        foreach ($arrayDistinct as $id => $count) {
            // mencari data obat berdasarkan id (obat yg dipilih)
            $medicine = Medicine::where('id', $id)->first();
            // ambil bagian column price dr hasil pencarian lalu kalikan dengan jumlah item duplikat sehingga akan menghasilkan total harga dr pembelian obat tersebut
            $subPrice = $medicine['price'] * $count;
            // pengecekan ketersediaan stock
            if ($medicine['stock'] < $count) {
                $valueFormBefore =[
                    'name_customer' => $request->name_customer,
                    'medicines' => $request->medicines
                ];
                $msg = 'Stock Obat ' . $medicine['name'] . ' Tidak cukup. Tersisa ' . $medicine['stock'];
                return redirect()->back()->with([
                    'failed' => $msg,
                    'valueFormBefore' => $valueFormBefore
                ]);
            } else {
                $medicine['stock'] -= $count;
                $medicine->save();
            }
    
            // struktur value column medicines menjadi multidimensi dengan dimensi kedua berbentuk array assoc dengan key "id", "name_medicine", "qty", "price", "sub_price"
            $arrayItem = [
                "id" => $id,
                "name_medicine" => $medicine['name'],
                "qty" => $count,
                "price" => $medicine['price'],
                "sub_price" => $subPrice,
            ];
    
            // masukkan struktur array tersebut ke array kosong yg disediakan sebelumnya
            array_push($arrayAssocMedicines, $arrayItem);
        }
    
        // total harga pembelian dari obat-obat yg dipilih
        $totalPrice = 0;
        // looping format array medicines baru
        foreach ($arrayAssocMedicines as $item) {
            // total harga pembelian ditambahkan dr keseluruhan sub_price data medicines
            $totalPrice += (int)$item['sub_price'];
        }
    
        // harga beli ditambah 10% ppn
        $priceWithPPN = $totalPrice + ($totalPrice * 0.01);
    
        // tambah data ke database
        $proses = Order::create([
            // data user_id diambil dari id akun kasir yg sedang login
            'user_id' => Auth::user()->id,
            'medicines' => $arrayAssocMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $priceWithPPN,
        ]);

        if ($proses) {
            // $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            return redirect()->route('kasir.order.print', $proses['id']);
        } else {
            // jika tidak berhasil, maka diarahkan kembali ke halaman form dengan pesan pemberitahuan
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian. Silahkan coba kembali dengan data yang sesuai!');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('order.kasir.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // 
    }
    public function downloadPDF($id) { 
        // panggil library PDF
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        $order = Order::find($id)->toArray(); 
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        view()->share('order',$order); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = Pdf::loadView('order.kasir.download-pdf', $order); 
        // download PDF file dengan nama tertentu
        return $pdf->download('struk.pdf'); 
    }
}
