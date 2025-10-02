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
        // Izinkan request jika pengguna sudah login
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
            'university_id' => 'required|exists:universities,id',
            'major' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:14',
            'phone' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
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
            'last_name.required' => 'Nama belakang wajib diisi.',
            'university_id.required' => 'Universitas wajib dipilih.',
            'university_id.exists' => 'Universitas tidak valid.',
            'major.required' => 'Jurusan wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxxxx.',
            'bio.max' => 'Bio maksimal 500 karakter.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat jpeg, jpg, atau png.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->phone) {
            $this->merge([
                'phone' => $this->normalizePhoneNumber($this->phone),
            ]);
        }
    }

    /**
     * Normalize phone number to a consistent format.
     */
    private function normalizePhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }
        
        if (!str_starts_with($phone, '+') && str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }
}