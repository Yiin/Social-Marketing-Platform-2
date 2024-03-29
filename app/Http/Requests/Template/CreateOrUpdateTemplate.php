<?php

namespace App\Http\Requests\Template;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateTemplate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($template = $this->route()->parameter('template')) {
            return $template->user_id == Auth::id();
        }
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
            'url' => '',
            'name' => 'required',
            'image_url' => '',
            'description' => '',
            'message' => '',
            'caption' => ''
        ];
    }
}
