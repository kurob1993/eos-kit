<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTravelRequest extends FormRequest
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
            "personnel_no" => "required",
            "start_date" => "required",
            "end_date" => "required",
            "tujuan" => "required",
            "keperluan" => "required|max:100",
            "kendaraan" => "required",
            "nopol" => "max:30",
            "jenis_spd" => "required",
            "stage_id" => "required",
            "kota" => "required',
            'lampiran' => 'required'|
                'mimetypes:image/jpeg,image/png,application/pdf', 
                'max:500'
                
                // new PermitHasAttachment(Input::get('permit_type'))
        ];
    }
}
