<?php

namespace App\Http\Requests\Client;

use App\Constants\Permission;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateClient extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->hasPermissionTo(Permission::MANAGE_CLIENTS)) {
            if ($this->route()->parameter('client')) {
                return $this->route()->parameter('client')->reseller_id == Auth::id();
            }
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

        if ($client = $this->route()->parameter('client')) {
            $unique = $unique->ignore($client->id);
        }

        return [
            'name' => 'required',
            'email' => ['required', 'email', $unique]
        ];
    }
}
