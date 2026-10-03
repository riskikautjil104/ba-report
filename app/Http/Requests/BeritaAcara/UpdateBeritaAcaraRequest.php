<?php

declare(strict_types=1);

namespace App\Http\Requests\BeritaAcara;

use App\Enums\BaPriority;
use App\Enums\BaStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBeritaAcaraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $beritaAcara = $this->route('beritaAcara');

        return $beritaAcara && $this->user()?->can('update', $beritaAcara);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'kategori_id' => ['required', 'exists:categories,id'],
            'lokasi' => ['required', 'string', 'max:255'],
            'prioritas' => ['required', new Enum(BaPriority::class)],
            'status' => ['required', new Enum(BaStatus::class)],
            'pelapor_nama' => ['required', 'string', 'max:255'],
            'pelapor_jabatan' => ['nullable', 'string', 'max:255'],
            'pelapor_unit' => ['required', 'string', 'max:255'],
            'pelapor_kontak' => ['nullable', 'string', 'max:100'],
            'keluhan' => ['required', 'string'],
            'hasil_pemeriksaan' => ['nullable', 'string'],
            'penyebab' => ['nullable', 'string'],
            'tindakan' => ['nullable', 'string'],
            'kebutuhan' => ['nullable', 'string'],
            'nama_vendor' => ['nullable', 'string', 'max:255'],
            'kontak_vendor' => ['nullable', 'string', 'max:255'],
            'catatan_vendor' => ['nullable', 'string'],
            'kesimpulan' => ['nullable', 'string'],
            'tindak_lanjut' => ['nullable', 'string'],
        ];
    }
}
