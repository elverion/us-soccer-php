<?php

namespace App\Validation;

/**
 * A data object used to represent the success status of a custom validation class
 */
class ValidationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $failReason = null,
    ) {
    }
}
