<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttachStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "stage_id" => "required|uuid|exists:stages,id",
            "event_id" => "required|uuid|exists:events,id",
            "date" => "nullable|date",
        ];
    }
}
