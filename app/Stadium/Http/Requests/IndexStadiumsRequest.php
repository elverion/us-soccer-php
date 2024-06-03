<?php

namespace App\Stadium\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexStadiumsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'int|min:1',
        ];
    }
}
