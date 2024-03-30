<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;

class ShareNewRequest extends FormRequest
{
    public function rules() : array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'time'            => 'required',
                    'userAccessToken' => 'required',
                    'userEmail'       => 'required|email|exists:google_user,email',
                    'userGoogleID'    => 'required|exists:google_user,google_id',
                    'userImg'         => 'required|exists:google_user,user_img_url',
                    'userName'        => 'required|exists:google_user,owner_name',
                    'shareNewsURL'    => 'required|url',
                ];
            case 'PUT':
                return [

                ];
            case 'DELETE':
                return [

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

    public function attributes()
    {
        return [
            'time'            => 'Timestamp',
            'userAccessToken' => 'User Access Token',
            'userEmail'       => 'Email',
            'userGoogleID'    => 'Google ID',
            'userImg'         => 'Image URL',
            'userName'        => 'Username',
            'shareNewsURL'        => 'Shared News URL',
        ];
    }
}
