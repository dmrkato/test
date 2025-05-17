<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetCommentListRequest extends FormRequest
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
            'page' => 'integer|nullable|min:1',
            'perPage' => 'integer|nullable|min:1|max:50',
            'sortBy' => 'string|nullable|in:user_name,email,date',
            'direction' => 'string|nullable|in:asc,desc',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ];
    }
}
