<?php

namespace App\Traits;

use Throwable;
use Illuminate\Support\Facades\Log;

trait HandlesRepositoryExceptions
{
    /**
     * Safely executes a given callback and handles exceptions that may occur during execution.
     * Logs success or error messages along with context and payload information.
     *
     * @param callable $callback The callback function to execute.
     * @param string $context A string representing the context of the operation, used for logging.
     * @param array $payload Optional data to include in the log messages.
     * 
     * @return mixed Returns the result of the callback if successful, or false if an exception occurs.
     */
    public function safeExecute(callable $callback, string $context, array $payload = [])
    {
        try {
            $result = $callback();
            Log::info("[$context] Success", ['data' => $payload]);
            return $result;
        } catch (Throwable $e) {
            Log::error("[$context] Error", [
                'error' => $e->getMessage(),
                'data' => $payload,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
