<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Supplier;

class SupplierRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $suppcode;

    public function __construct($suppcode)
    {
        $this->suppcode = $suppcode;
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
        $supplier_name = Supplier::where('supplier_name',$value)->count();
        $supplier_code = Supplier::where('supplier_name',$value)->first('suppcode');

        if($supplier_name == 1){
            if($this->suppcode == $supplier_code->suppcode){
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
        return 'Supplier name is already exists.';
    }
}
