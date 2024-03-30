<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
{

    public function rules() : array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'google_id'    => 'required',
                    'owner_name'   => 'required|',
                    'user_img_url' => 'required|url',
                    'email'        => 'required|email',

                    'wallet_account' => 'nullable|between:42,42',
                    'birthday'       => 'nullable|date',
                    'gender'         => 'nullable',
                    'country'        => 'nullable|string',
                    'state'          => 'nullable|string',
                    'zip'            => 'nullable|integer',
                    'address'        => 'nullable|string',
                ];
            case 'PUT':
                return [
                    'uuid'                    => 'required|exists:todo_lists,uuid',
                    'title'                   => 'required',
                    'status'                  => 'required',
                    'a_person_in_charge_uuid' => 'required|exists:users,uuid',
                    'start_date'              => 'required|date',
                    'end_date'                => 'required|date|after:start_date',
                    'description'             => 'required'
                ];

            case 'DELETE':
                return [
                    'uuid' => 'required|exists:todo_lists,uuid',
                ];

        }
    }

    /*
    * true => Passed
    * false => Denied
    * TODO Use Auth::user()->can(permission) to verify if the user has permission to perform this operation
     */
    public function authorize() : bool
    {
        return true;
    }
}
