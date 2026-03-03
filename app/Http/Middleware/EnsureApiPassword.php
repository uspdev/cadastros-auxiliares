<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiPassword
{
    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->guard('web')->check()
            || $this->isInternalThemeRequest($request)
            || $this->isSameOriginRequest($request)
        ) {
            return $next($request);
        }

        $configuredPassword = (string) config('cadastros-auxiliares.password', '');

        if ($configuredPassword === '') {
            return $next($request);
        }

        $providedPassword = $request->query('password')
            ?? $request->header('X-Cadastros-Auxiliares-Password');

        if (!is_string($providedPassword) || !hash_equals($configuredPassword, $providedPassword)) {
            return response()->json([
                'message' => 'Não autorizado.',
            ], 401);
        }

        return $next($request);
    }

    private function isInternalThemeRequest(Request $request): bool
    {
        return $request->header('X-UspTheme-Mensagens-Internal') === '1';
    }

    private function isSameOriginRequest(Request $request): bool
    {
        $currentHost = mb_strtolower((string) $request->getHost());

        if ($currentHost === '') {
            return false;
        }

        $origin = (string) $request->headers->get('Origin', '');
        $referer = (string) $request->headers->get('Referer', '');

        $originHost = $origin !== '' ? mb_strtolower((string) parse_url($origin, PHP_URL_HOST)) : '';
        $refererHost = $referer !== '' ? mb_strtolower((string) parse_url($referer, PHP_URL_HOST)) : '';

        return ($originHost !== '' && hash_equals($currentHost, $originHost))
            || ($refererHost !== '' && hash_equals($currentHost, $refererHost));
    }
}
