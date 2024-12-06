<?php

namespace App\Http\Middleware;

use App\Models\Borrowing;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class UpdateBorrowingStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Logika untuk update status borrowing
        Borrowing::where('return_date', '<', Carbon::now()->subDay())
            ->where('status_id', '!=', 4)
            ->update(['status_id' => 4]);

        return $next($request);
    }
}
