<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            // Trim whitespace dari title dan description
            'title' => $this->title ? trim($this->title) : null,
            'description' => $this->description ? trim($this->description) : null,
            
            // Set default priority jika tidak ada
            'priority' => $this->priority ?? 'medium',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title: wajib, string, 5-255 karakter
            'title' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            
            // Description: wajib, string, minimal 20 karakter (biar jelas)
            'description' => [
                'required',
                'string',
                'min:20',
            ],
            
            // Priority: wajib, harus salah satu dari low/medium/high
            // Menggunakan 'in' rule = WHITELIST approach (lebih aman!)
            'priority' => [
                'required',
                'in:low,medium,high',
            ],
            
            // Category: opsional, jika diisi harus string max 100
            'category' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Title messages
            'title.required' => 'Judul tiket wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.min' => 'Judul minimal :min karakter.',
            'title.max' => 'Judul maksimal :max karakter.',
            
            // Description messages
            'description.required' => 'Deskripsi tiket wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'description.min' => 'Deskripsi minimal :min karakter agar permasalahan jelas.',
            
            // Priority messages
            'priority.required' => 'Silakan pilih prioritas tiket.',
            'priority.in' => 'Prioritas yang dipilih tidak valid. Pilih: Low, Medium, atau High.',
            
            // Category messages
            'category.string' => 'Kategori harus berupa teks.',
            'category.max' => 'Kategori maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul tiket',
            'description' => 'deskripsi',
            'priority' => 'prioritas',
            'category' => 'kategori',
        ];
    }

    protected function passedValidation(): void
    {
        // Contoh: strip HTML tags dari description untuk keamanan tambahan
        // (Defense in depth - meskipun Blade sudah auto-escape)
        $this->merge([
            'description' => strip_tags($this->description),
        ]);
    }
}
