<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Facades\App\Repositories\Contracts\PostRepositoryInterface as PostRepository;

class Post
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $model = PostRepository::view($request->route('uuid'));

        if (!$model) abort(404, 'Page not found');

        return $next($request);
    }
}
