<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CheckIdempotency
{
    public function handle(Request $request, Closure $next): Response
    {
        $idempotencyKey = $request->header('Idempotency-Key');

        if (! $idempotencyKey) {
            throw new BadRequestHttpException('Idempotency-Key header is required.');
        }

        $cacheKey = 'idempotency:referral:' . $idempotencyKey;

        // Check if the response is already cached
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);

            return response($cachedData['content'], 200)
                ->header('Content-Type', 'application/ld+json');
        }

        $response = $next($request);

        // Only cache successful creation responses (201 Created)
        if ($response->getStatusCode() === 201) {
            Cache::put($cacheKey, [
                'status' => 200, // Stored as 200 for subsequent requests
                'content' => $response->getContent(),
            ], now()->addHours(24));
        }

        return $response;
    }
}
