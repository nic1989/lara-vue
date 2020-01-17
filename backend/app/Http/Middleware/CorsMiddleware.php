<?php
namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Closure;

class CorsMiddleware
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
        /* return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                -header('Access-Control-Allow-Headers', 'Content-Type, X-Api-Token, Authorization, Origin, Content-Length, Accept, X-Requested-With'); */

        $headers = $this->getHeaders();
        $response = $next($request);
        return $response->withHeaders($headers);

        /* $headers = $this->getHeaders();
        
        if ($request->isMethod('OPTIONS')) {
            return response('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);

        // Specially for downloading operations. We cannot add headers to this monster
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        return $response->withHeaders($headers); */
    }

    protected function getHeaders()
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => implode(',', [
                'Origin', 'Authorization',
                'Content-Type', 'Content-Length', 'Accept',
                'X-Requested-With', 'X-Api-Token'
            ]),
            'Access-Control-Allow-Methods' => implode(',', [
                'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
            ]),
            'Access-Control-Max-Age' => '86400',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer',
            'Feature-Policy' => "microphone 'none'",
            'Content-Security-Policy' => "default-src 'self';",
            'X-Xss-Protection' => "1; mode=block"
        ];
    }
}
