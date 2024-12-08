<?php

namespace App\Http\Middleware;


use App\Models\Borrowing;
use App\Models\Invoice;
use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpdateBorrowingStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Update borrowing yang sudah lewat tanggal pengembalian
        Borrowing::where('return_date', '<', Carbon::now()->subDay())
            ->where('status_id', '!=', 4) // Status 'returned'
            ->update(['status_id' => 4]); // Update status menjadi 'overdue'

        // Ambil data invoice dalam chunk untuk efisiensi memori
        Invoice::with(['borrowings.book', 'user'])->chunk(100, function ($invoices) {
            foreach ($invoices as $invoice) {
                $totalAmount = 0;
                $hasFinedBorrowing = false;
                $overdueBooks = []; // Simpan daftar buku yang terlambat

                foreach ($invoice->borrowings as $borrowing) {
                    if ($borrowing->status_id === 4) { // Jika status 'overdue'
                        $totalAmount += 20000; // Tambah denda
                        $hasFinedBorrowing = true;

                        // Simpan judul buku yang terlambat
                        if ($borrowing->book) {
                            $overdueBooks[] = $borrowing->book->title;
                        }
                    }
                }

                // Update total_amount pada invoice
                $invoice->update(['total_amount' => $totalAmount]);

                // Jika ada borrowing yang terlambat, update status invoice dan buat notifikasi
                if ($hasFinedBorrowing) {
                    $invoice->update(['status' => 'fined']);

                    // Cek apakah notifikasi sudah dibuat
                    $existingNotification = Notification::where('user_id', $invoice->user_id)
                        ->where('type', 'due')
                        ->where('invoice_id', $invoice->id)
                        ->first();

                    if (!$existingNotification) {
                        // Buat notifikasi baru
                        Notification::create([
                            'user_id'    => $invoice->user_id,
                            'admin_id'   => null,
                            'book_id'    => null,
                            'type'       => 'due',
                            'message'    => 'Invoice #' . $invoice->id . ': Buku-buku berikut telah terlambat dikembalikan: ' . implode(', ', $overdueBooks),
                            'is_read'    => false,
                            'invoice_id' => $invoice->id,
                        ]);
                    }
                }
            }
        });

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
