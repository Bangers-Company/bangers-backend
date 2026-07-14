<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id|unique:event_timetables,event_id',
            'name' => 'required|string|max:255',
            'is_official' => 'boolean',
            'is_public' => 'boolean',
        ];
    }
}
