<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScanKtpWithGemini
{
    public static function extract(string $imagePath): array
    {
        try {
            if (!file_exists($imagePath)) {
                Log::error('KTP image not found', [
                    'path' => $imagePath,
                ]);

                return [];
            }

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);

            $prompt = <<<PROMPT
Kamu adalah OCR untuk KTP Indonesia.

Ekstrak data berikut dari gambar KTP ini.

Kembalikan HANYA JSON valid tanpa markdown dan tanpa penjelasan.

{
  "nama": "",
  "tanggal_lahir": "YYYY-MM-DD",
  "jenis_kelamin": "",
  "alamat": ""
}

ATURAN PENTING:
- jenis_kelamin hanya boleh berisi:
  - "Laki-laki"
  - "Perempuan"
- Jangan gunakan:
  - Pria
  - Wanita
  - Laki laki
  - Laki-Laki
  - perempuan
- tanggal_lahir harus format YYYY-MM-DD.
- alamat harus berupa alamat lengkap pada KTP.

Jika data tidak ditemukan, isi dengan string kosong.
PROMPT;

            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'inline_data' => [
                                            'mime_type' => $mimeType,
                                            'data' => $imageData,
                                        ],
                                    ],
                                    [
                                        'text' => $prompt,
                                    ],
                                ],
                            ],
                        ],
                    ]
                );

            if ($response->failed()) {
                Log::error('Gemini OCR failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [];
            }

            $text = $response->json('candidates.0.content.parts.0.text');

            Log::info('Gemini OCR raw response', [
                'text' => $text,
            ]);

            if (!$text) {
                return [];
            }

            $text = preg_replace('/```json/i', '', $text);
            $text = preg_replace('/```/', '', $text);
            $text = trim($text);

            $data = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON from Gemini', [
                    'json_error' => json_last_error_msg(),
                    'response_text' => $text,
                ]);

                return [];
            }

            $jenisKelamin = trim($data['jenis_kelamin'] ?? '');

            if (
                str_contains(strtolower($jenisKelamin), 'laki') ||
                str_contains(strtolower($jenisKelamin), 'pria')
            ) {
                $jenisKelamin = 'Laki-laki';
            } elseif (
                str_contains(strtolower($jenisKelamin), 'perempuan') ||
                str_contains(strtolower($jenisKelamin), 'wanita')
            ) {
                $jenisKelamin = 'Perempuan';
            }

            return [
                'nama' => trim($data['nama'] ?? ''),
                'tanggal_lahir' => trim($data['tanggal_lahir'] ?? ''),
                'jenis_kelamin' => $jenisKelamin,
                'alamat' => trim($data['alamat'] ?? ''),
            ];
        } catch (\Throwable $e) {
            Log::error('Gemini OCR Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [];
        }
    }
}