<?php

declare(strict_types=1);

namespace App\Http\Requests\BeritaAcara;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isActive();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB limit
                'mimes:jpg,jpeg,png,webp,pdf',
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
