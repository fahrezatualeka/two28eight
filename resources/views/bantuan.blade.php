@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Bantuan (FAQ)</h2>
        
        <!-- Kontainer untuk daftar FAQ -->
        <div id="faq-list" class="space-y-4">
            <!-- Data pertanyaan dan jawaban akan dilooping di sini -->
            @php
                $faqs = [
                    [
                        'question' => 'Bagaimana cara melacak pesanan saya?',
                        'answer' => 'Anda bisa melacak pesanan Anda melalui menu "Status Pesanan" di kolom atas website. Setelah pesanan dikirim, kami akan mengirimkan nomor resi melalui nomor whatsapp.'
                    ],
                    [
                        'question' => 'Apa saja metode pembayaran yang tersedia?',
                        'answer' => 'Kami menggunakan 1 metode pembayaran yaitu transfer bank BCA melalui informasi pembayaran yang tertera.'
                    ],
                    [
                        'question' => 'Bisakah saya mengembalikan atau menukar produk?',
                        'answer' => 'Tidak, Produk yang dibeli tidak dapat dikembalikan dengan alasan apapun.'
                    ],
                    [
                        'question' => 'Bagaimana jika produk yang saya terima rusak?',
                        'answer' => 'Jika Anda menerima produk yang rusak, harap segera hubungi kami. Kami akan mengatur penggantian atau pengembalian dana penuh setelah produk rusak diverifikasi.'
                    ],
                ];
            @endphp

            @foreach($faqs as $faq)
            <div class="border-b border-gray-200">
                <button class="faq-toggle w-full py-4 text-left font-semibold text-gray-800 focus:outline-none flex justify-between items-center transition-colors hover:text-gray-500">
                    <span>{{ $faq['question'] }}</span>
                    <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="faq-answer max-h-0 overflow-hidden transition-[max-height] duration-500 ease-in-out">
                    <p class="py-4 text-gray-600 pr-10">{{ $faq['answer'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const faqButtons = document.querySelectorAll(".faq-toggle");

        faqButtons.forEach(button => {
            button.addEventListener("click", () => {
                const answer = button.nextElementSibling;
                const icon = button.querySelector("svg");

                // Toggle class 'hidden' untuk menampilkan/menyembunyikan jawaban
                if (answer.classList.contains("max-h-0")) {
                    answer.classList.remove("max-h-0");
                    answer.classList.add("max-h-[500px]"); // Ganti dengan nilai yang cukup besar
                    icon.classList.add("rotate-45");
                } else {
                    answer.classList.add("max-h-0");
                    answer.classList.remove("max-h-[500px]");
                    icon.classList.remove("rotate-45");
                }
            });
        });
    });
</script>
@endsection
