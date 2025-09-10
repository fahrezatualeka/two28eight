<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class TestApi extends Component
{
    // Properti untuk menyimpan input pengguna
    public string $origin = '';
    public string $destination = '';
    public int $weight = 1000; // Default 1000 gram = 1 kg
    public string $courier = 'jnt'; // Kurir default

    // Properti untuk menyimpan hasil API
    public array $originResults = [];
    public array $destinationResults = [];
    public array $shippingCosts = [];

    // Properti untuk menampilkan status loading
    public bool $isLoading = false;

    // Properti untuk menyimpan ID kota yang dipilih
    public ?string $originId = null;
    public ?string $destinationId = null;

    // Panggil API untuk mencari kota asal secara real-time
    public function updatedOrigin($value)
    {
        if (empty($value)) {
            $this->originResults = [];
            return;
        }

        try {
            $response = Http::withHeader('key', config('rajaongkir.api_key'))
                ->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                    'search' => $value,
                    'limit'  => 5,
                ]);

            if ($response->successful()) {
                $this->originResults = $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            $this->originResults = [];
        }
    }

    // Panggil API untuk mencari kota tujuan secara real-time
    public function updatedDestination($value)
    {
        if (empty($value)) {
            $this->destinationResults = [];
            return;
        }

        try {
            $response = Http::withHeader('key', config('rajaongkir.api_key'))
                ->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                    'search' => $value,
                    'limit'  => 5,
                ]);

            if ($response->successful()) {
                $this->destinationResults = $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            $this->destinationResults = [];
        }
    }

    // Menghitung biaya ongkos kirim
    public function calculateCost()
    {
        // Pastikan semua data yang diperlukan sudah terisi
        if (empty($this->originId) || empty($this->destinationId) || empty($this->weight) || empty($this->courier)) {
            $this->shippingCosts = ['error' => 'Harap isi semua kolom dengan benar.'];
            return;
        }

        $this->isLoading = true;
        
        try {
            // Asumsi API endpoint untuk cek ongkir
            // Anda perlu menyesuaikan ini dengan dokumentasi API RajaOngkir yang Anda gunakan
            $response = Http::withHeader('key', config('rajaongkir.api_key'))
                ->post('https://rajaongkir.komerce.id/api/v1/cost/domestic', [
                    'origin'      => $this->originId,
                    'destination' => $this->destinationId,
                    'weight'      => $this->weight,
                    'courier'     => $this->courier,
                ]);

            if ($response->successful()) {
                $this->shippingCosts = $response->json('data') ?? [];
            } else {
                $this->shippingCosts = ['error' => 'Gagal mendapatkan data ongkir. Silakan coba lagi.'];
            }
        } catch (\Exception $e) {
            $this->shippingCosts = ['error' => 'Terjadi kesalahan. ' . $e->getMessage()];
        } finally {
            $this->isLoading = false;
        }
    }

    // Fungsi untuk menetapkan kota asal dari hasil pencarian
    public function setOrigin($id, $name)
    {
        $this->originId = $id;
        $this->origin = $name;
        $this->originResults = []; // Sembunyikan hasil pencarian
    }

    // Fungsi untuk menetapkan kota tujuan dari hasil pencarian
    public function setDestination($id, $name)
    {
        $this->destinationId = $id;
        $this->destination = $name;
        $this->destinationResults = []; // Sembunyikan hasil pencarian
    }

    public function render()
    {
        return view('livewire.test-api');
    }
}
