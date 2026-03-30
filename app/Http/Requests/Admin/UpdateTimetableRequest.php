<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'is_public' => 'boolean',
            'entries' => 'array',
            'entries.*.stage_id' => 'required|exists:stages,id',
            'entries.*.act_id' => 'required|exists:acts,id',
            'entries.*.start_time' => 'required|date',
            'entries.*.end_time' => 'required|date|after:entries.*.start_time',
        ];
    }
}
