<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAttendanceQuotaRequest extends FormRequest
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
          'personnel_no' => 'required',
          'start_date' => 'required',
          'day_assignment' => 'required',
          'from' => 'required',
          'to' => 'required',
          'overtime_reason_id' => 'required',
        ];
    }
}