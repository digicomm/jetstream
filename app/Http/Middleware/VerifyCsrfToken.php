<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
    /**
     * Overwrite CSRF verification cookie to allow multiple sites
     *
     * @var array<int, string>
     */
    protected function newCookie($request, $config): Cookie
    {
        return new Cookie(
            "XSRF-TOKEN".($config['xsrf_token_name'] ? '-'.$config['xsrf_token_name'] : ''),
            $request->session()->token(),
            $this->availableAt(60 * $config['lifetime']),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null,
        );
    }
}
