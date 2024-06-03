<?php

namespace App\Stadium\Http\Requests;

use App\Stadium\Validation\StadiumCsvRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreStadiumsRequest extends FormRequest
{
    /** Normally we would do authorization checks. But since we're disregarding
     * auth for the purposes of this code test, pretend we did something insightful here.
     */
    public function authorize(): bool
    {
        return true;
    }

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
