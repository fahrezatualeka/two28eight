<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KomerceOngkirController extends Controller
{
    private $apiKey = "P2k1Mlwsdba3777c52ff050f5YV9S4HH";

    // ✅ Mapping Provinsi → ID Kota Default
    private $provinceToCity = [
        1 => 421, 2 => 399, 3 => 114, 4 => 145, 5 => 23,
        6 => 15, 7 => 180, 8 => 282, 9 => 58, 10 => 152,
        11 => 151, 12 => 39, 13 => 110, 14 => 501, 15 => 17,
        16 => 77, 17 => 202, 18 => 444, 19 => 135, 20 => 265,
        21 => 431, 22 => 210, 23 => 85, 24 => 178, 25 => 131,
        26 => 120, 27 => 234, 28 => 165, 29 => 502, 30 => 146,
        31 => 195, 32 => 400, 33 => 222, 34 => 245
    ];

    private $manualShippingRates = [
        2 => 55000, // Maluku
        5 => 30000, // Jawa Barat
        19 => 35000, // DI Yogyakarta
        10 => 40000, // DKI Jakarta
        // Tambahkan provinsi lainnya sesuai kebutuhan
    ];
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        return response()->json($response->json());
    }

    public function getCost(Request $request)
    {
        $courier = strtolower($request->courier);
        $province = (int) $request->destination;
    
        $ongkir = $this->manualShippingRates[$courier][$province] ?? 60000;
    
        return response()->json(['success' => true, 'ongkir' => $ongkir]);
    }
}