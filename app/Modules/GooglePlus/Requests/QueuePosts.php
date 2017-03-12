<?php

namespace App\Modules\GooglePlus\Requests;

use Illuminate\Foundation\Http\FormRequest;


class QueuePosts extends FormRequest
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
            'client_id' => 'required|exists:users,id',
            'template_id' => 'required|exists:templates,id',
            'delay' => 'required',
            'queue' => 'required',
        ];
    }
}
