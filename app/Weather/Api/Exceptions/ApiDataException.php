<?php

namespace App\Weather\Api\Exceptions;

use RuntimeException;
use Throwable;

class ApiDataException extends RuntimeException
{
    public function __construct(string $message = "An error was encountered while fetching data from the weather API", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Update the exception's message to describe what expected data was missing, and,
     * optionally, what was searched.
     */
    public function setMissingData(string $property, ?string $location = null): static
    {
        $this->message = "The data fetched from OpenWeather API was malformed; did not contain `{$property}`";
        if ($location !== null) {
            $this->message .= ". Searched location: `{$location}`";
        }

        return $this;
    }
}
