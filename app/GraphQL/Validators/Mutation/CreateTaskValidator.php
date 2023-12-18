<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

class CreateTaskValidator extends Validator
{

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title' => 'The validation.attributes.title field is required.',
            'description' => 'The validation.attributes.description field is required.',];
    }
}
