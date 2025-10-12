<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\InstitutionRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * ValidationController
 * 
 * handle validasi step by step untuk form registrasi
 */
class ValidationController extends Controller
{
    /**
     * validasi step registrasi student
     */
    public function validateStudentStep(Request $request)
    {
        $step = $request->input('step');

        // buat instance dari form request hanya untuk mendapatkan aturannya
        $formRequest = new StudentRegisterRequest();
        $allRules = $formRequest->rules();
        $allMessages = $formRequest->messages();
        
        // tentukan field mana yang akan divalidasi untuk setiap langkah
        $rulesForStep = [];
        if ($step == 1) {
            $fields = ['first_name', 'last_name', 'email', 'whatsapp_number', 'photo'];
        } elseif ($step == 2) {
            $fields = ['university_id', 'major', 'nim', 'semester'];
        } elseif ($step == 3) {
            $fields = ['username', 'password', 'password_confirmation'];
        } else {
            return response()->json(['message' => 'langkah tidak valid.'], 400);
        }

        // filter aturan validasi hanya untuk field di langkah saat ini
        foreach ($fields as $field) {
            if (isset($allRules[$field])) {
                $rulesForStep[$field] = $allRules[$field];
            }
        }
        
        // lakukan validasi secara manual menggunakan validator facade
        $validator = Validator::make($request->all(), $rulesForStep, $allMessages);

        if ($validator->fails()) {
            // jika validasi gagal, kembalikan error dalam format json
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // jika validasi berhasil
        return response()->json(['message' => 'validasi berhasil.']);
    }

    /**
     * validasi step registrasi institution
     * PERBAIKAN: sesuaikan dengan 4 steps di UI
     */
    public function validateInstitutionStep(Request $request)
    {
        $step = $request->input('step');

        // buat instance dari form request hanya untuk mendapatkan aturannya
        $formRequest = new InstitutionRegisterRequest();
        $allRules = $formRequest->rules();
        $allMessages = $formRequest->messages();
        
        // tentukan field mana yang akan divalidasi untuk setiap langkah
        // PERBAIKAN: pisahkan menjadi 4 steps sesuai UI
        $rulesForStep = [];
        if ($step == 1) {
            // step 1: data instansi
            $fields = ['institution_name', 'institution_type', 'official_email'];
        } elseif ($step == 2) {
            // step 2: lokasi
            $fields = ['address', 'province_id', 'regency_id'];
        } elseif ($step == 3) {
            // step 3: penanggung jawab
            $fields = ['pic_name', 'pic_position', 'phone_number', 'logo', 'verification_document', 'website', 'description'];
        } elseif ($step == 4) {
            // step 4: akun & verifikasi
            $fields = ['username', 'password', 'password_confirmation'];
        } else {
            return response()->json(['message' => 'langkah tidak valid.'], 400);
        }

        // filter aturan validasi hanya untuk field di langkah saat ini
        foreach ($fields as $field) {
            if (isset($allRules[$field])) {
                $rulesForStep[$field] = $allRules[$field];
            }
        }
        
        // lakukan validasi secara manual menggunakan validator facade
        $validator = Validator::make($request->all(), $rulesForStep, $allMessages);

        if ($validator->fails()) {
            // jika validasi gagal, kembalikan error dalam format json
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // jika validasi berhasil
        return response()->json(['message' => 'validasi berhasil.']);
    }
}