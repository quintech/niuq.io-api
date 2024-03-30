<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class NewRequest extends FormRequest
{
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title'                      => 'required',
                    'url'                        => 'required',
                    'ad_fontes_media_uuid'       => 'required|exists:ad_fontes_media,uuid',
                    'media_bias_fact_check_uuid' => 'required|exists:media_bias_fact_check,uuid',
                    'fact_mata_context'          => 'required',
                    'author'                     => 'nullable',
                    'description'                => 'nullable',
                    'addDate'                    => 'nullable',
                    'image'                      => 'nullable',
                ];
            case 'PUT':
                return [
                    'uuid'                    => 'required|exists:todo_lists,uuid',
                    'title'                   => 'required',
                    'status'                  => 'required',
                    'a_person_in_charge_uuid' => 'required|exists:users,uuid',
                    'start_date'              => 'required|date',
                    'end_date'                => 'required|date|after:start_date',
                    'description'             => 'required',
                    'author'                  => 'nullable',
                    'addDate'                 => 'nullable',
                    'image'                   => 'nullable',
                ];

            case 'DELETE':
                return [
                    'uuid' => 'required|exists:todo_lists,uuid',
                ];
        }
    }

    //    public function attributes()
    //    {
    //        return [
    //          'url' => 'URL'
    //        ];
    //    }

    /*
    * true => Pass
    * false => Reject
    * TODO Use Auth::user()->can(permission) to verify if the user has permission to perform this operation
     */
    public function authorize(): bool
    {
        return true;
    }
}
