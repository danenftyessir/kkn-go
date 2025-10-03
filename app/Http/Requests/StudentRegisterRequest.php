<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StudentRegisterRequest extends FormRequest
{
    /**
     * tentukan apakah user diizinkan untuk melakukan request ini
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * aturan validasi yang diterapkan pada request
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                // validasi email harus dari domain universitas (.ac.id atau .edu)
                'regex:/^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9-]+\.)*ac\.id$|^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9-]+\.)*edu$/'
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9._-]+$/',
                'min:4'
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(app()->isProduction() ? 3 : 0) // Cek hanya di produksi
            ],
            'university_id' => [
                'required',
                'exists:universities,id'
            ],
            'major' => [
                'required',
                'string',
                'max:100'
            ],
            'nim' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/'
            ],
            'semester' => [
                'required',
                'integer',
                'min:1',
                'max:14'
            ],
            'whatsapp_number' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,12}$/'
            ],
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:2048' // 2MB
            ]
        ];
    }

    /**
     * pesan error kustom untuk validasi
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'nama depan wajib diisi',
            'first_name.regex' => 'nama depan hanya boleh berisi huruf',
            'last_name.required' => 'nama belakang wajib diisi',
            'last_name.regex' => 'nama belakang hanya boleh berisi huruf',
            'email.required' => 'email wajib diisi',
            'email.email' => 'format email tidak valid',
            'email.unique' => 'email sudah terdaftar',
            'email.regex' => 'gunakan email universitas (.ac.id atau .edu)',
            'username.required' => 'username wajib diisi',
            'username.unique' => 'username sudah digunakan',
            'username.regex' => 'username hanya boleh berisi huruf, angka, titik, underscore, dan strip',
            'username.min' => 'username minimal 4 karakter',
            'password.required' => 'password wajib diisi',
            'password.confirmed' => 'konfirmasi password tidak cocok',
            'university_id.required' => 'universitas wajib dipilih',
            'university_id.exists' => 'universitas tidak valid',
            'major.required' => 'jurusan wajib diisi',
            'nim.required' => 'nim wajib diisi',
            'nim.regex' => 'nim hanya boleh berisi angka',
            'semester.required' => 'semester wajib dipilih',
            'semester.min' => 'semester minimal 1',
            'semester.max' => 'semester maksimal 14',
            'whatsapp_number.required' => 'nomor whatsapp wajib diisi',
            'whatsapp_number.regex' => 'format nomor whatsapp tidak valid',
            'profile_photo.image' => 'file harus berupa gambar',
            'profile_photo.mimes' => 'foto profil harus berformat jpeg, jpg, atau png',
            'profile_photo.max' => 'ukuran foto profil maksimal 2MB'
        ];
    }

    /**
     * normalize data sebelum validasi
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email ?? ''),
            'username' => strtolower($this->username ?? ''),
            'first_name' => ucwords(strtolower($this->first_name ?? '')),
            'last_name' => ucwords(strtolower($this->last_name ?? '')),
            // normalize nomor whatsapp
            'whatsapp_number' => $this->normalizePhoneNumber($this->whatsapp_number ?? '')
        ]);
    }

    /**
     * normalize nomor telepon
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // hapus spasi dan karakter non-numeric
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // convert 08xx menjadi 628xx
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // tambahkan + jika belum ada
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }

    /**
     * atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'nama depan',
            'last_name' => 'nama belakang',
            'email' => 'email',
            'username' => 'username',
            'password' => 'password',
            'university_id' => 'universitas',
            'major' => 'jurusan',
            'nim' => 'nim',
            'semester' => 'semester',
            'whatsapp_number' => 'nomor whatsapp',
            'profile_photo' => 'foto profil'
        ];
    }
}