<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Reglas actualizadas y nuevas
            'name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:50', // Límite actualizado
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'document_type' => ['required', 'string', Rule::in(['CI', 'PASSPORT'])],
            'document_number' => ['required', 'integer', 'digits_between:5,12'],
        ];
    }
    /*
     *  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
     * */
}
