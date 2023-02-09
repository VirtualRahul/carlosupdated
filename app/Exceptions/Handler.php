<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Models\DbLog;
use sagar\BugFile\BugFile;
use Illuminate\Support\Facades\Auth;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // $log = new DbLog;
        // $log->request = json_encode($request);
        // $log->action = $request->fullUrl();
        // $log->exception = $exception->getMessage().' on line '.$exception->getLine(). ' file '.$exception->getFile();
        // $log->save();

        // $message = ' msg ' . $exception->getMessage() . ' on line No. ' . $exception->getLine() . ' on file ' . $exception->getFile();
        // $bug = new BugFile();
        // $bug->causedBy(Auth::id());
        // $bug->causedAt(\URL::current());
        // $bug->setSeverity(env('Severity'));
        // $bug->customData($request->all());
        // $bug->log($exception);
        // $bug->setMessage($message);
        // $bug->loggedBy('Admin');
        // $bug->save();

        return parent::render($request, $exception);
    }
}
