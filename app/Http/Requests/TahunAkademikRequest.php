<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TahunAkademikRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tahunAkademikId = $this->route('tahun_akademik') ? $this->route('tahun_akademik')->id : null;

        return [
            'tahun' => [
                'required',
                'string',
                'max:9',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_akademik', 'tahun')->ignore($tahunAkademikId),
                function ($attribute, $value, $fail) {
                    $years = explode('/', $value);
                    if (count($years) === 2) {
                        $startYear = (int) $years[0];
                        $endYear = (int) $years[1];
                        if ($endYear !== $startYear + 1) {
                            $fail('Tahun akademik harus berurutan (contoh: 2024/2025).');
                        }
                        if ($startYear < 2020 || $startYear > 2050) {
                            $fail('Tahun akademik harus dalam rentang yang wajar (2020-2050).');
                        }
                    }
                }
            ],
            // Semester is automatically set to 'Ganjil' - no validation needed
            'tanggal_mulai' => [
                'required',
                'date',
                'after_or_equal:' . date('Y-m-d', strtotime('-2 years')),
                'before_or_equal:' . date('Y-m-d', strtotime('+5 years'))
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
                'before_or_equal:' . date('Y-m-d', strtotime('+6 years'))
            ],
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tahun.required' => 'Tahun akademik wajib diisi.',
            'tahun.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2024/2025).',
            'tahun.unique' => 'Tahun akademik sudah ada dalam sistem.',
            // Semester validation messages removed - automatically set to Ganjil
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh terlalu lama di masa lalu.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai tidak boleh terlalu jauh di masa depan.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'tanggal_selesai.before_or_equal' => 'Tanggal selesai tidak boleh terlalu jauh di masa depan.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tahun' => 'tahun akademik',
            'semester' => 'semester',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        
        // Log validation errors for debugging
        Log::warning('Tahun Akademik validation failed', [
            'errors' => $errors->toArray(),
            'input' => $this->all()
        ]);

        parent::failedValidation($validator);
    }
}
