<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasDublicateSchedule()) {
                $validator->errors()->add('schedule', 'Schedule already exists');
            }
        });
    }

    public function hasDublicateSchedule():bool
    {
        return Schedule::query()->where('subject_id', $this->subject_id)
            ->where('teacher_id', $this->teacher_id)
            ->where('group_id', $this->group_id)
            ->where('pair', $this->pair)
            ->where('week_day', $this->week_day)
            ->where('date', $this->date)
            ->where('id', '!=', $this->id)
            ->exists();
    }
}
