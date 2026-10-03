<?php

declare(strict_types=1);

namespace App\Http\Requests\AuditKebutuhan;

use App\Enums\AuditStatus;
use App\Enums\BaPriority;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAuditKebutuhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isActive() && ! $this->user()->isVendor();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_audit' => ['required', 'date'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'lokasi_gedung' => ['required', 'string', 'max:255'],
            'nama_responden' => ['required', 'string', 'max:255'],
            'jabatan_responden' => ['nullable', 'string', 'max:255'],
            'kontak_responden' => ['nullable', 'string', 'max:100'],
            'kategori_id' => ['required', 'exists:categories,id'],
            'keluhan_kendala' => ['required', 'string'],
            'keinginan_harapan' => ['required', 'string'],
            'rekomendasi_it' => ['nullable', 'string'],
            'prioritas' => ['required', new Enum(BaPriority::class)],
            'status' => ['required', new Enum(AuditStatus::class)],
            'nama_vendor' => ['nullable', 'string', 'max:255'],
            'catatan_vendor' => ['nullable', 'string'],
            'tanda_tangan_responden' => ['nullable', 'string'],
        ];
    }
}
