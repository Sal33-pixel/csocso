<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addTeamRequest extends FormRequest
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
     * @return array<string, mixed>
     */

    //csapat összeállítás validálás
    public function rules()
    {
        return [
            'team_name'     => 'required|max:30|unique:App\Models\Team,team_name',
            'selectDoorman' => 'required|different:selectStriker',
            'selectStriker' => 'required|different:selectDoorman',
        ];
    }
}
