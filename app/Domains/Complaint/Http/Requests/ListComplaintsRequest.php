<?php

namespace App\Domains\Complaint\Http\Requests;

use App\Domains\Project\Enums\ProjectCostRangeEnum;
use App\Domains\Project\Enums\ProjectPriorityEnum;
use App\Domains\Project\Enums\ProjectStatusEnum;
use App\Domains\Project\Traits\NormalizesEnumFilters;
use App\Domains\ProjectStudy\Enums\ProjectStudyStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListComplaintsRequest extends FormRequest
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
            'search'                      => ['nullable', 'string', 'max:100'],
            'per_time'                    => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'                        => ['nullable', 'integer', 'min:1'],
        ];
    }
}

