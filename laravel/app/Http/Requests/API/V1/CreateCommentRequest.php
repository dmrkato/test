<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCommentRequest extends FormRequest
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
            'user_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'home_page' => 'nullable|string|url|max:255',
            'text' => 'required|string|max:510',
            'parent_id' => 'nullable|numeric|exists:App\Models\Comment,id',
            'attachments' => 'nullable|array',
            'attachments.*' => [
                'file', 'mimes:jpeg,png,jpg,gif,txt',
                function ($attribute, $value, $fail) {
                    $mime = $value->getMimeType();

//                    if (str_starts_with($mime, 'image/')) {
//                        [$width, $height] = getimagesize($value);
//                        if ($width > 320 || $height > 240) {
//                            $fail('Image must be not more than 320x240 pixels');
//                        }
//                    } else
                    if ($mime === 'text/plain') {
                        if ($value->getSize() > 100 * 1024) {
                            $fail('Text file size must me not more than 100 KB');
                        }
                    }
                },
            ],
        ];
    }
}
