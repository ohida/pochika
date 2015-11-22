<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Response;
use Pochika;

class PochikaMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            Pochika::init();
            $response = $next($request);
            Pochika::end();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

        return $response;
    }

    protected function handleException($e)
    {
        if (env('APP_DEBUG')) {
            throw $e;
        }

        switch (get_class($e)) {
            case 'Pochika\Exception\NotInitializedException':
                return $this->renderError('pochika');
            default:
                return $this->renderError('error');
        }
    }

    protected function renderError($message, $description = null, $status_code = 500)
    {
        return new Response(view('errors.message')->with([
            'message' => $message,
            'description' => $description,
        ]), $status_code);
    }
}
