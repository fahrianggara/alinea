<?php

namespace App\Http\Middleware;

use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil borrowings yang jatuh tempo hari ini
        $borrowingsDueToday = Borrowing::where('return_date', Carbon::now()->startOfDay())
            ->where('status_id', '!=', 4) // Status yang belum terlambat
            ->get();

        foreach ($borrowingsDueToday as $borrowing) {
            // Cek apakah notifikasi untuk pengembalian hari ini sudah dibuat
            $existingNotification = Notification::where('user_id', $borrowing->user_id)
                ->where('book_id', $borrowing->book_id)
                ->where('type', 'due')
                ->first();

            if (!$existingNotification) {
                // Buat notifikasi peringatan untuk pengembalian hari ini
                Notification::create([
                    'user_id'  => $borrowing->user_id,
                    'admin_id' => null, // Sesuaikan jika admin diperlukan
                    'book_id'  => $borrowing->book_id,
                    'type'     => 'due',
                    'message'  => 'Ayo kembalikan buku berjudul "' . $borrowing->book->title . '" hari ini!',
                    'is_read'  => false,
                ]);
            }
        }

        return $next($request);
    }
}
