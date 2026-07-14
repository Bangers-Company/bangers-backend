<?php

namespace App\Http\Requests\Timetables;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimetableEntriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entry_ids' => 'required|array',
            'entry_ids.*' => 'exists:timetable_entries,id',
        ];
    }
}
