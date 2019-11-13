<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
class DirectorPost extends FormRequest
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
            'no' => 'required',
            'empnik' => 'required',
            'empname' => 'required',
            'empposid' => 'required',
            'emp_hrp1000_s_short' => 'required',
            'emppostx' => 'required',
            'emportx' => 'required',
            'emppersk' => 'required',
            'LSTUPDT' => 'required',
            'ttl' => 'required'
        ];
    }
}
