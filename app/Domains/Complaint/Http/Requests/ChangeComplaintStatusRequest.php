<?php

namespace App\Domains\Complaint\Http\Requests;

use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeComplaintStatusRequest extends FormRequest
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
            'status' => [Rule::enum(ComplaintStatusEnum::class)],
            'version' => ['required', 'integer', 'min:1'],
        ];
    }


}
