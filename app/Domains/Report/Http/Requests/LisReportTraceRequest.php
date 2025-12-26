<?php

namespace App\Domains\Report\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LisReportTraceRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'filter'                      => ['nullable', 'array'],
            'per_time'                    => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'                        => ['nullable', 'integer', 'min:1'],
        ];
    }
}

