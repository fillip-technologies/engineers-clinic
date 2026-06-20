<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StartCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'location' => ['required', 'string', 'max:255'],
            'college' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'stream' => ['nullable', 'string', 'max:255'],
            'selected_courses' => ['nullable', 'array', 'max:3'],
            'selected_courses.*' => ['integer', 'exists:courses,id'],
            'selected_project_nos' => ['nullable', 'array', 'max:3'],
            'selected_project_nos.*' => ['integer'],
        ];
    }
}
