<?php

namespace App\Http\Requests\Reseller;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateReseller extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->hasPermissionTo(User::MANAGE_RESELLERS)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = Rule::unique('users');

        if ($reseller = $this->route()->parameter('reseller')) {
            $unique = $unique->ignore($reseller->id);
        }

        return [
            'name' => 'required',
            'email' => ['required', 'email', $unique],
        ];
    }
}
