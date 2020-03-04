<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Masterfile;

class DescriptionRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    
    public $stockcode;

    public function __construct($stockcode)
    {
        $this->stockcode = $stockcode;
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
        $stockdesc = Masterfile::where('stockdesc',$value)->count();
        $stockcode = Masterfile::where('stockdesc',$value)->first('stockcode');
        
        if($stockdesc == 1){
            if($this->stockcode == $stockcode->stockcode){
                return true;
            }
            else{
                return false;
            }
        }
        return true;   
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Description is alreadt exists. Please check description.";
    }
}
