<?php

namespace App\Http\Middleware;

use App\Http\Traits\TraitController;
use App\User;
use Closure;

class AccessApiKeyMiddleware
{
    use TraitController;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!User::where([
            ['api_key', '=', $request->header('x-api-key') ?? null],
            ['api_key_expired', '>=', date('YmdHis')],
            ['is_active', '=', true]
        ])->first())
            return $this->response_json([], 401,'Access denied for api key');

        return $next($request);
    }
}
