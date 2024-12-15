<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::post('/classify-object', function (Request $request) {
    // Validasi input
    $request->validate([
        'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Simpan file sementara
    $file = $request->file('image');
    $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

    // API URL dan API Key Roboflow
    $apiUrl = 'https://detect.roboflow.com/klasifikasi-produk/4?api_key=fC8vXFLDrKrKvssWHpZy'; // Ganti dengan endpoint Roboflow Anda
    //$apiKey = 'fC8vXFLDrKrKvssWHpZyY'; // Ganti dengan API Key Anda
    $apiKey = env('ROBOFLOW_API_KEY');

    // Kirim permintaan ke API Roboflow
    $response = Http::attach(
        'file', 
        fopen(storage_path('app/' . $filePath), 'r'),
        $file->getClientOriginalName()
    )->post("$apiUrl?api_key=$apiKey");

    // Periksa respons dari Roboflow
    if ($response->failed()) {
        return response()->json(['error' => 'Gagal melakukan klasifikasi.'], 500);
    }

    // Hasil klasifikasi
    $result = $response->json();
    return response()->json(['result' => $result]);

    return response()->json($response->json());

});

