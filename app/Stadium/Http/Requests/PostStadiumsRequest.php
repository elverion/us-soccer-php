<?php

namespace App\Stadium\Http\Requests;

use App\Stadium\Validation\StadiumCsvRule;
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
                new StadiumCsvRule(),
            ]
        ];
    }
}
