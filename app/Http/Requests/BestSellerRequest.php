<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating NYT Best Sellers API query parameters.
 */
class BestSellerRequest extends FormRequest
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
            'author' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:32',
            'offset' => 'nullable|integer|min:0',
        ];
    }
}
