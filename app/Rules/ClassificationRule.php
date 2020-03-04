<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Classification;

class ClassificationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $classification = Classification::where('classificationdesc',$value)->count();
        
        if($classification == 1){
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Classification does not exists. Please check classification again.';
    }
}
