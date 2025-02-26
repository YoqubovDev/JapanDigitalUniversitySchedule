<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject_id'=>'required|exists:subjects,id',
            'user_id'=>'required|exists:users.id',
            'group_id'=>'required|exists:groups,id',
            'room_id'=>'required|exists:rooms,id',
            'pair'=>'required|integer|between:1,7',
            'week_day'=>'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'date'=>'required|date',
        ];
    }
}
