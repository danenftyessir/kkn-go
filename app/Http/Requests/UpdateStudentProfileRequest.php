<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // izinkan request jika pengguna sudah login
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
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'university_id' => 'required|exists:universities,id',
            'major' => 'required|string|max:100',
            'nim' => 'required|string|max:20',
            'semester' => 'required|integer|min:1|max:14',
            'whatsapp_number' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50',
            'interests' => 'nullable|array',
            'interests.*' => 'string|max:50',
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
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max' => 'Nama depan maksimal 50 karakter.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'last_name.max' => 'Nama belakang maksimal 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'university_id.required' => 'Universitas wajib dipilih.',
            'university_id.exists' => 'Universitas tidak valid.',
            'major.required' => 'Jurusan wajib diisi.',
            'major.max' => 'Jurusan maksimal 100 karakter.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.max' => 'NIM maksimal 20 karakter.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 14.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxxxx atau +62xxxxxxxxx.',
            'bio.max' => 'Bio maksimal 500 karakter.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat jpeg, jpg, atau png.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2MB.',
            'skills.array' => 'Skills harus berupa array.',
            'skills.*.string' => 'Setiap skill harus berupa teks.',
            'skills.*.max' => 'Setiap skill maksimal 50 karakter.',
            'interests.array' => 'Interests harus berupa array.',
            'interests.*.string' => 'Setiap interest harus berupa teks.',
            'interests.*.max' => 'Setiap interest maksimal 50 karakter.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->whatsapp_number) {
            $this->merge([
                'whatsapp_number' => $this->normalizePhoneNumber($this->whatsapp_number),
            ]);
        }
    }

    /**
     * Normalize phone number to a consistent format.
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // hapus semua karakter selain angka dan +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // convert 08xx ke 628xx
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // tambahkan + di depan jika belum ada dan dimulai dengan 62
        if (!str_starts_with($phone, '+') && str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }
}