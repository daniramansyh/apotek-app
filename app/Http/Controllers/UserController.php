<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserController extends Controller
{

    // pake Request karna dipanggil dari form 
    public function loginProses(Request $request) 
    {
        $request->validate([
            'email' => 'required|email:dns', //dns adalah domain email
            'password' => 'required',
        ]);

        //ambil data dari input satukan dalam array
        // user dijalankan ketika si attempt dijalankan
        $user = $request->only(['email', 'password']);
        //cek kecocokan email password (pw dienkripsi), lalu simpan pada class Auth
        if (auth::attempt($user)) {
            //jika berhasil login, arahkan ke landing_page
            return redirect()->route('landing_page');
        } else {
            return redirect()->back()->with('failed', 'Gagal Login! Silahkan coba lagi');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah Logout');
    }
    /**
     * Display a listing of the resource.
     */
    // Request $request -> mengambil data dari form nya (di php sebelumnya : $_POST/$_GET)'
    //Metode ini digunakan untuk mengambil dan menampilkan daftar pengguna dari database.x
    // public function index(Request $request) adalah metode yang digunakan untuk menampilkan daftar pengguna
    public function index(Request $request)
    {
        //menampilkan data dari model yg menyimpan data obat
        // all() -> mengambil semua data dari table users model User
        // orderBy('nama_kolom', 'asc/desc') -> mengurutkan data berdasarkan kolom tertentu
        // asc (ascending) -> urutkan data dari kecil ke besar (a-z/0-9)
        // desc (descending) ->  urutkan data dari besar ke kecil (z-a/9-0)
        // all() -> tanpa proses filter apapun
        // filter -> mengambil get()/paginate()/simpalePaginate()
        // simplePaginate(angka) -> mengambil data dengan pagination per halamannya jumlah data disimpan di kurung (5)
        // where('nama_kolom', 'operator', 'nilai') -> mencari data berdasarkan kolom tertentu dan isi tertentu (isinya yg dr input)
        // operator where : =, <, >, <=, >=, <>, LIKE
        // mengambil isi input : $request->name_input
        // appends : menambahkan/membawa request pagination (data-data pagination tidak berubah meskipun ada request)

        // orderBy = $request->sort_email ? 'email' : 'name'; untuk mengurutkan data berdasarkan kolom tertentu
        $orderBy = $request->sort_email ? 'email' : 'name';

        // $users = User::where('name', 'LIKE', '%'.$request->cari.'%')->orderBy($orderBy, 'ASC')->simplePaginate(5)->appends($request->all()); fungsi disini 
        $users = User::where('name', 'LIKE', '%'.$request->cari.'%')->orderBy($orderBy, 'ASC')->simplePaginate(5)->appends($request->all());
        // compact() -> mengirimkan data ($) agar data $nya bisa dipake di blade
        return view('pages.kelola_akun', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */

    //public function create() adalah metode yang digunakan untuk menampilkan form pengguna
    public function create()
    {

        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */

     //public function store(Request $request) adalah metode yang digunakan untuk menyimpan data pengguna
    public function store(Request $request)
    {
        // Validation rules for user creation
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required'
        ], [
            'name.required' => 'Nama Pengguna Harus Diisi!',
            'email.required' => 'Email Harus Diisi!',
            'email.email' => 'Format Email Tidak Valid!',
            'email.unique' => 'Email Sudah Terdaftar!',
            'role.required' => 'Role Harus Dipilih!',
            'password.required' => 'Password Harus Diisi!'
        ]);
        
        // User User::create fungsinya untuk membuat data baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,

            //bcrypt adalah fungsi untuk enkripsi password
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambah Pengguna!');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id) adalah metode yang digunakan untuk menampilkan form ubah data
    public function edit($id)
    {
        //$user = User::find($id); mengambil data berdasarkan id
        $user = User::find($id);

        //compact untuk mengirimkan data
        //return view('user.edit', compact('user')); mengirimkan data ke view
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */

    // proses pengiriman edit baru ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|'
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('kelola_user.kelola')->with('success', 'Berhasil Mengubah Data');
    }

    
    /**
     * Remove the specified resource from storage.
     */

    //public function destroy($id) adalah metode yang digunakan untuk menghapus data


    // delete

    // destroy($id) Function:
    // Fungsi ini menerima satu parameter yaitu $id, yang merepresentasikan ID pengguna yang akan dihapus.
    public function destroy($id)
    {
        // User::where('id', $id)->delete(); fungsi ini digunakan untuk menghapus data berdasarkan id 
        User::where('id', $id)->delete();

        // redirect()->back()->with('success', 'Berhasil Menghapus Data'); fungsi ini digunakan untuk kembali ke halaman sebelumnya
        // dan akan menampilkan pesan success 
        return redirect()->back()->with('success', 'Berhasil Menghapus Data Obat!');
    }
    public function exportExcel() {
        return Excel::download(new UsersExport, 'data_user.xlsx');
    }
}