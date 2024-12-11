@extends('templates.app')
{{-- extends : mengimport/memanggil file view (biasanya untuk template nya, isi dr template merupakan content tetap/content yg selalu ada di setiap halaman) --}}

{{-- section : mengisi element html ke yield dengan nama yg sama ke file templatenya --}}
@section('content-dinamis')
@if (Session::get('failed'))
    <div class="alert alert-danger">{{ Session::get('failed') }}</div>
@endif

<section id="home" class="bg-primary text-white text-center py-5">
    <div class="container">
      <h1 class="display-4">Selamat Datang {{ Auth::user()->name }} di Apotek Sehat</h1>
      <p class="lead">Temukan kebutuhan kesehatan Anda di sini dengan produk yang terpercaya dan layanan yang terbaik.</p>
      <a href="#services" class="btn btn-light mt-3">Pelajari Lebih Lanjut</a>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-5">
    <div class="container text-center">
      <h2 class="mb-4">Layanan Kami</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Konsultasi Apoteker</h5>
              <p class="card-text">Dapatkan konsultasi dengan apoteker berpengalaman untuk kesehatan Anda.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Pemesanan Obat Online</h5>
              <p class="card-text">Pesan obat dengan mudah melalui layanan online kami.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Layanan Antar</h5>
              <p class="card-text">Kami menyediakan layanan antar langsung ke rumah Anda.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products" class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-4">Produk Unggulan</h2>
      <div class="row">
        <div class="col-md-3">
          <div class="card shadow-sm">
            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Produk 1">
            <div class="card-body">
              <h5 class="card-title">Vitamin C</h5>
              <p class="card-text">Vitamin untuk meningkatkan daya tahan tubuh.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Produk 2">
            <div class="card-body">
              <h5 class="card-title">Paracetamol</h5>
              <p class="card-text">Obat untuk mengurangi demam dan nyeri.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Produk 3">
            <div class="card-body">
              <h5 class="card-title">Suplemen Herbal</h5>
              <p class="card-text">Suplemen herbal untuk kesehatan harian Anda.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Produk 4">
            <div class="card-body">
              <h5 class="card-title">Minyak Kayu Putih</h5>
              <p class="card-text">Minyak aromaterapi untuk kehangatan tubuh.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-5">
    <div class="container text-center">
      <h2 class="mb-4">Testimoni Pelanggan</h2>
      <div class="row">
        <div class="col-md-4">
          <blockquote class="blockquote">
            <p class="mb-0">Pelayanan sangat cepat dan memuaskan!</p>
            <br>
            <footer class="blockquote-footer">Andi, Jakarta</footer>
          </blockquote>
        </div>
        <div class="col-md-4">
          <blockquote class="blockquote">
            <p class="mb-0">Produk sangat lengkap dan berkualitas.</p>
            <br>
            <footer class="blockquote-footer">Rani, Bandung</footer>
          </blockquote>
        </div>
        <div class="col-md-4">
          <blockquote class="blockquote">
            <p class="mb-0">Harga bersaing dan pengiriman cepat.</p>
            <br>
            <footer class="blockquote-footer">Budi, Surabaya</footer>
          </blockquote>
        </div>
      </div>
    </div>
  </section>
@endsection