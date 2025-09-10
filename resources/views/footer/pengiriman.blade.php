@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10 text-justify">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">pengiriman</h2>
        <p>pengiriman produk dibuat 5 status pengiriman, diantara nya:</p>
        <br>
        <p>1. menunggu pembayaran: pembeli harus segera membayar pembayaran produk pemesan nya sebelum 24 jam, jika tidak dibayarkan sistem akan otomatis membatalkan pesanan nya</p>
        <br>
        <p>2. menunggu verifikasi: admin akan memverifikasi terlebih dahulu bukti pembayaran yang anda kirimkan, jika benar maka status tersebut akan di ubah ke diproses untuk pengiriman produk lebih lanjut, jika tidak maka pesanan anda akan dibatalkan otomatis atau dihapus oleh admin</p>
        <br>
        <p>3. diproses: persiapan produk yang dipesan dari pembeli berdasarkan pada informasi data pengiriman, ukuran, serta jumlah yang dipesan</p>
        <br>
        <p>4. dikirim: produk anda telah dikirimkan ke jasa pengiriman sesuai dengna metode pengiriman yang anda pilih, setelah mengirimkan produk tersebut admin akan mengirimkan nomor resi dari jasa pengiriman</p>
        <br>
        <p>5. selesai: produk telah selesai atau diterima oleh pembeli</p>
    </div>
</div>
@endsection