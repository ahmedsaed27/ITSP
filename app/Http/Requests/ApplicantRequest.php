<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class ApplicantRequest extends FormRequest
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
            'cv' => "required|mimetypes:application/pdf|max:10000",
            'name' => 'required|string',
            'email' => 'required|email|unique:applicant,email',
            'password' => 'required|min:6',
            'phone' => 'required|regex:/01[1250][0-9]{8}/',
            'citys_id' => 'required|numeric',
            'area' => 'required|string',
            'birthYear' => 'required|date_format:Y',
            'gender' => 'required|numeric',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
