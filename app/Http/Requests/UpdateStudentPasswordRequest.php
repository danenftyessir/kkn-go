<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash; // <-- PENTING: Tambahkan ini
use Illuminate\Validation\Rules\Password;

class UpdateStudentPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // PERBAIKAN: Gunakan closure untuk validasi manual
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Password saat ini yang Anda masukkan salah.');
                    }
                },
            ],
            // Pastikan aturan password baru yang kuat tetap ada
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ];
    }

    /**
     * Get the custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            // Pesan untuk aturan Password::class akan di-generate otomatis dan sudah bagus
        ];
    }
}