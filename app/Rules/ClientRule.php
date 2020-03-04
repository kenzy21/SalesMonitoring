<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Client;

class ClientRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $client_code;
    
    public function __construct($client_code)
    {
        $this->client_code = $client_code;
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
        $clientcode = Client::where('client_name',$value)->first('client_code');
        $client_name = Client::where('client_name',$value)->count();

        if($client_name == 1){
            if($this->client_code == $clientcode->client_code){
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
        return 'Client name is already exists.';
    }
}
