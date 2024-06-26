<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'account'    => 'required|unique:users,account',
                    'password'   => 'required',
                ];

        }
    }

    public function attributes()
    {
        return [
            'account' => 'Account',
            'password' => 'Password',
        ];
    }
}
