<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AbsenceType;

class PermitHasAttachment implements Rule
{
    protected $permitType;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($permitType)
    {
        $this->permitType = $permitType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        dd($value);
        // return true;

        // $hasAttachment = AbsenceType::where('subtype', $this->permitType)
        //     ->first()
        //     ->has_attachment;

        // return false;

        // return $this->permitType;

        // if ($hasAttachment)
        //     return true;
        // else 
        //     return false;
   
        // dd($value);
   
        // return empty($value);
   
        // if ($hasAttachment)
        //     return ($value) ? true : false;
        // else
        //     return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
