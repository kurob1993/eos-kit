<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreExternalActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_organisasi' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'posisi' => 'required',
          ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'jenis_kegiatan.required' => 'Kolom Jenis Kegiatan Tidak Boleh Kosong',
            'start_date.required'  => 'Kolom Tangaal Mulai Tidak Boleh Kosong',
            'end_date.required'  => 'Kolom Tangaal Berakhir Tidak Boleh Kosong',
            'posisi.required'  => 'Kolom Posisi Tidak Boleh Kosong',
            'keterangan.required'  => 'Kolom Keterangan Tidak Boleh Kosong',
        ];
    }

}
