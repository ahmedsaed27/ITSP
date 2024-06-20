<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class JobRequest extends FormRequest
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
            'image' => 'required|image',
            'postion' => 'required',
            'discription' => 'required',
            'job_level' => 'required|in:0,1,2',
            'job_type' => 'required|in:0,1',
            'job_place' => 'required|in:0,1,2',
            'range_salary' => 'required',
            'skills' => 'required|array',
            'requirments' => 'required',
            'categories_id' => 'required|exists:categories,id',
            'departments_id' => 'required|exists:departments,id',
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
