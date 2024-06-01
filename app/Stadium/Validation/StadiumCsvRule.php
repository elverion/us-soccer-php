<?php

namespace App\Stadium\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Custom validation rule to validate an uploaded file.
 * 
 * The rule will only pass if the attribute being validated is an UploadedFile,
 * has all required CSV headers, and all data passes data type expectations.
 */
class StadiumCsvRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var UploadedFile $value */
        if (!($value instanceof UploadedFile)) {
            $fail('The :attribute is not a valid CSV file.');
            return;
        }

        $result = StadiumCsvValidator::validate($value->get());
        if (!$result->success) {
            $fail($result->failReason ?? 'Unknown error occurred');
        }
    }
}
