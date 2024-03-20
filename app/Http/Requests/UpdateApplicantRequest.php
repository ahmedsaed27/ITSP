<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateApplicantRequest extends FormRequest
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
            'cv' => "sometimes|mimetypes:application/pdf|max:10000",
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:applicant,email'.$this->id,
            'password' => 'sometimes|min:6',
            'phone' => 'sometimes|regex:/01[1250][0-9]{8}/',
            'citys_id' => 'sometimes|numeric',
            'area' => 'sometimes|string',
            'birthYear' => 'sometimes|date_format:Y',
            'gender' => 'sometimes|numeric',
            'images' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->getMessageBag()->toArray()
            ], 422)
        );
    }
}
