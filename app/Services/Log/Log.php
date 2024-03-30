<?php


namespace App\Services\Log;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as BaseLog;

trait Log
{
    //TODO Rewrite as logging methods using Laravel Logger package
    /**
     * Log with debug level
     * @param  mixed  $message
     */
    public function logDebug($message)
    {
        ActivityLogger::activity('debug', $message);
    }

    /**
     * Log with info level including IP
     * @param  mixed  $message
     */
    public function logInfo($message)
    {
        ActivityLogger::activity('info', $message);
    }

    /**
     * Log with warning level including IP
     * @param  mixed $message
     */
    public function logWarning($message)
    {
        ActivityLogger::activity('warning', $message);
    }

    /**
     * Log with error level including IP
     * @param  mixed $message
     */
    public function logError($message)
    {
        ActivityLogger::activity('error', $message);
    }
}
