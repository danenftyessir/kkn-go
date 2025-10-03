<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegisterRequest; // Tetap gunakan ini untuk mengambil rules
use Illuminate\Http\Request;                  // Gunakan Request generik di method
use Illuminate\Support\Facades\Validator;   // Gunakan Validator facade

class ValidationController extends Controller
{
    public function validateStudentStep(Request $request) // PERBAIKAN 1: Gunakan Request, bukan StudentRegisterRequest
    {
        $step = $request->input('step');

        // Buat instance dari Form Request hanya untuk mendapatkan aturannya
        $formRequest = new StudentRegisterRequest();
        $allRules = $formRequest->rules();
        
        // Tentukan field mana yang akan divalidasi untuk setiap langkah
        $rulesForStep = [];
        if ($step == 1) {
            $fields = ['first_name', 'last_name', 'email', 'whatsapp_number', 'profile_photo'];
        } elseif ($step == 2) {
            $fields = ['university_id', 'major', 'nim', 'semester'];
        } elseif ($step == 3) {
            $fields = ['username', 'password'];
        } else {
            return response()->json(['message' => 'Langkah tidak valid.'], 400);
        }

        // Filter aturan validasi hanya untuk field di langkah saat ini
        foreach ($fields as $field) {
            if (isset($allRules[$field])) {
                $rulesForStep[$field] = $allRules[$field];
            }
        }
        
        // PERBAIKAN 2: Lakukan validasi secara manual menggunakan Validator facade
        $validator = Validator::make($request->all(), $rulesForStep);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan error dalam format JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Jika validasi berhasil
        return response()->json(['message' => 'Validasi berhasil.']);
    }
}