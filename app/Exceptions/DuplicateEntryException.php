<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class DuplicateEntryException extends Exception
{
    protected string $errorMessage;
    protected string $errorDetails;

    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param string $errorDetails Additional details about the error (optional)
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", string $errorDetails = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorMessage = $message;
        $this->errorDetails = $errorDetails;

        // Optionally log this exception
        $this->logException();
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Get the additional error details.
     *
     * @return string
     */
    public function getErrorDetails(): string
    {
        return $this->errorDetails;
    }

    /**
     * Optionally, log the exception details for later review.
     */
    protected function logException(): void
    {
        Log::error('Duplicate Entry Exception: ' . $this->getMessage(), [
            'errorDetails' => $this->errorDetails,
            'exception' => $this
        ]);
    }

    // You can add more methods here if needed
}
