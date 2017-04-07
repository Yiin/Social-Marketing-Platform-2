<?php

namespace App\Modules\Linkedin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnlockLinkedinAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
            'code' => 'required'
        ];
    }
}
