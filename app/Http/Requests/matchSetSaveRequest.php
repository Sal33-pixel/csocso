<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class matchSetSaveRequest extends FormRequest
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
    public function rules()
    {
        return [
            'date'          => 'required|date',
            'team_1_result' => 'required|integer|min:0|max:10|different:team_2_result',
            'team_2_result' => 'required|integer|min:0|max:10|different:team_1_result',
        ];
    }
}
