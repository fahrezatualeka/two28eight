@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10 text-justify">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">cara berbelanja</h2>
        <p>1. masuk di menu utama, dan melihat produk yang ingin di pesan</p>
        <br>
        <p>2. klik gambar produk tersebut dan pilih ukuran dan jumlah yang ingin di beli</p>
        <br>
        <p>3. produk dapat di tambahkan ke keranjang belanja atau dapat melakukan pembelian sekarang</p>
        <br>
        <p>4. pengisian data sebagai informasi pengiriman pada produk, pastikan data yang di isikan benar agar tidak terjadi kesalah pahaman terkait pengiriman produk</p>
        <br>
        <p>5. setelah selesai melakukan pengisian data pengiriman, maka langsung di arahkan ke halaman konfirmasi pembayaran, terdapat konfirmasi pengisian data dan informasi data pembayaran yang akan dibayarkan</p>
        <br>
        <p>6. selesai melakukan pembayaran, jangan lupa kirimkan bukti pembayaran di kolom yang disediakan untuk upload bukti pembayaran, pastikan jangan sampai salah upload bukti karna admin akan melakukan verifikasi terlebih dahulu sebelum mengirimkan produk</p>
        <br>
        <p>7. klik tombol kirim (bayar langsung) jika anda melukan pembayaran pada saat itu, dan terdapat tombol kembali (bayar nanti) di halaman status pesanan jika anda ingin membayaran pesanan nya nanti namun sebelum 24 jam pembayaran anda harus dilakukan jika tidak dibayarkan maka sistem otomatis membatalkan pesanan anda</p>
        <br>
        <p>8. selesai melakukan pembayaran anda akan tetap dapat melihat status pesanan anda menggunakan nomor pesanan anda yang diberikan setelah produk anda di checkout</p>
    </div>
</div>
@endsection