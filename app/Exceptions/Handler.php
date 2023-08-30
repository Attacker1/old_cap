<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Exception\NotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Если хочет кто то JSON - возврат ему в таком же формате
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  \Exception  $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {

        if ($request->ajax() || $request->wantsJson() || $request->header('Content-Type') == 'application/json')
        {
            $json = [
                'success' => false,
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            self::log($exception, true);
            return response()->json($json, 400);
        }

        if ($this->isHttpException($exception))
        {

            self::log($exception);
            switch ($exception->getStatusCode()) {

                case 404:

                    //return $this->renderHttpException('');
                    break;

                case 501:

                    //return $this->renderHttpException($exception);
                    break;

                case 429:
                    //return $this->renderHttpException($exception);
                    break;

                default:
                    //return parent::render($request, $exception);
                    break;
            }
        }

        return parent::render($request, $exception);

    }

    /**
     * @param $exception
     * @param bool $ajax
     */
    public function log($exception, $ajax = false){

        $data = [
            'ip' => \request()->ip(),
            'Auth' => auth()->guard('admin')->user()->name ?? 'Не зарегистрирован',
            'URL' => \request()->url(),
            'Ajax' => $ajax,
            'Error Code' => @$exception->getCode() ?? '—',
            'REQUEST' => json_encode(\request()->all()),
            'Exception' => json_encode($exception),
            'Message' => json_encode($exception->getMessage()),
        ];

        Log::channel('exceptions')->info($data);


    }
}
