<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'errNum' => 401,
                    'msg' => 'not_authenticated_user'
                ]);
            } else {
                return redirect()->route('admin.login.page');
            }
        });
        $exceptions->render(function (HttpException $e, Request $request) use ($exceptions) {
            if ($request->is('api/*')) {
                $exceptions->respond(function (Response $response) {
                    $msg = 'Error';
                    if ($response->getStatusCode() === 419) {
                        $msg = 'The page expired, please try again.';
                    } elseif ($response->getStatusCode() === 500) {
                        $msg = 'server Error.';
                    } elseif ($response->getStatusCode() === 404) {
                        $msg = 'Route Not found.';
                    }
                    return response()->json([
                        'status' => false,
                        'errNum' => $response->getStatusCode(),
                        'msg' => $msg
                    ]);
                });
            } else {
                return redirect()->route('login');
            }
        });
    })->create();
