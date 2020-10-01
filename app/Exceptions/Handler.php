<?php

namespace App\Exceptions;

use Exception,ReflectionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Str;
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
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if(Str::lower($request->segment(1)) == "api" OR $request->header('host') == env("API_URL","") ){
            $url = explode(".", $request->getRequestUri());
            $format = (is_array($url) AND isset($url[1])) ? $url[1] : "json";
            $format = $format == "xml" ? "xml" : "json";
            $response = [];
            if($exception){
                switch(get_class($exception)){
                    case "UnexpectedValueException":
                    case "BadMethodCallException":
                    case "ErrorException":
                    case "ReflectionException":
                    case "Symfony\Component\Debug\Exception\FatalErrorException":
                    case "Symfony\Component\Debug\Exception\FatalThrowableError":
                    case "InvalidArgumentException":
                    case "Exception":
                        $response = array(
                            "msg" => "Server error: {$exception->getMessage()}.",
                            "status" => FALSE,
                            'status_code' => "APP_ERROR"
                            );
                        $status_code = 500;
                    break;
                    case "Illuminate\Database\QueryException":
                        $response = array(
                            "msg" => "Server error: {$exception->getMessage()}.",
                            "status" => FALSE,
                            'status_code' => "DB_ERROR"
                            );
                        $status_code = 500;
                    break;
                    case "Symfony\Component\HttpKernel\Exception\NotFoundHttpException":
                        $status_code = $exception->getStatusCode();
                        $response = array(
                            "msg" => "METHOD : {$request->server()["REQUEST_METHOD"]},API : {$request->getRequestUri()} not found.",
                            "status" => FALSE,
                            'status_code' => "NOT_FOUND"
                            );
                    break;
                    case "Tymon\JWTAuth\Exceptions\TokenBlacklistedException":
                        $response = array(
                            "msg" => "Invalid/Expired token.",
                            "status" => FALSE,
                            'status_code' => "INVALID_TOKEN"
                            );
                        $status_code = 401;
                    break;
                    case "Illuminate\Auth\AuthenticationException":
                        $response = array(
                            "msg" => "Session already closed.",
                            "status" => FALSE,
                            'status_code' => "ACCOUNT_LOGOUT"
                            );
                        $status_code = 423;
                    break;
                    case "Tymon\JWTAuth\Exceptions\TokenExpiredException":
                        $response = array(
                            "msg" => "Expired token.",
                            "status" => FALSE,
                            'status_code' => "EXPIRED_TOKEN"
                            );
                        $status_code = 423;
                    break;
                    case "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException":
                        $status_code = $exception->getStatusCode();

                        $response = array(
                            "msg" => "{$request->server()["REQUEST_METHOD"]} METHOD API : {$request->getRequestUri()} not allowed.",
                            "status" => FALSE,
                            'status_code' => "METHOD_NOT_ALLOWED"
                            );
                    break;


                    default:
                    dd(get_class($exception));exit;
                }

                switch(Str::lower($format)){
                    case 'json' :
                        return response()->json($response, $status_code);
                    break;
                    case 'xml' :
                        return response()->xml($response, $status_code);
                    break;
                }
            }
                
            
        }
        no_error:
        return parent::render($request, $exception);
    }
}
