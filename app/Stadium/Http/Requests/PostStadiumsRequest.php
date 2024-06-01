<?php

namespace App\Stadium\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class PostStadiumsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'stadiums' => [
                'required',
                File::types(['csv']),
            ]
        ];
    }
}
