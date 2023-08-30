<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class DoliApiException extends Exception
{

    protected $dontReport = [
        DoliApiException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        @Log::channel('doli')->critical('Fail:');
    }
}
