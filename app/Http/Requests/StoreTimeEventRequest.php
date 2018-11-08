<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTimeEventRequest extends FormRequest
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
        // time_events form elements
        // personnel_no, check_date, check_time, deduction,
        // permit_type, attachment, note, delegation (if have subordinates)        
        return [
            'personnel_no' => 'required',
            'check_date' => 'required',
            'check_time' => 'required',
            'time_event_type_id' => 'required',
            'note' => 'required|max:100',
        ];
    }
}
