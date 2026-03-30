<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
            "location" => "nullable|string|max:255",
            "start_date" => "sometimes|required|date",
            "end_date" => "sometimes|required|date|after_or_equal:start_date",
            "banner_media_id" => "nullable|uuid|exists:media,id",
        ];
    }
}
