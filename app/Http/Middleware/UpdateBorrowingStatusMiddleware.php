<?php

namespace App\Http\Middleware;

use App\Models\Borrowing;
use App\Models\Invoice;
use App\Models\Notification;
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
     
         // Ambil semua invoice dengan relasi borrowings
         $invoices = Invoice::with('borrowings', 'user')->get();
     
         foreach ($invoices as $invoice) {
             $totalAmount = 0;
             $hasFinedBorrowing = false;
             $overdueBooks = []; // Menyimpan judul buku yang terlambat
     
             foreach ($invoice->borrowings as $borrowing) {
                 if ($borrowing->status_id === 4) {
                     // Tambahkan Rp20.000 untuk setiap borrowing dengan status_id 4
                     $totalAmount += 20000;
                     $hasFinedBorrowing = true;
     
                     // Simpan judul buku yang terlambat
                     $overdueBooks[] = $borrowing->book->title;
                 }
             }
     
             // Update total_amount pada invoice
             $invoice->update([
                 'total_amount' => $totalAmount,
             ]);
     
             // Jika ada borrowing dengan status 4, ubah status invoice menjadi "fined"
             if ($hasFinedBorrowing) {
                 $invoice->update([
                     'status' => 'fined',
                 ]);
     
                 // Cek apakah notifikasi sudah dibuat untuk invoice ini
                 $existingNotification = Notification::where('user_id', $invoice->user_id)
                     ->where('type', 'due')
                     ->where('invoice_id', $invoice->id) // Tambahkan pengecekan berdasarkan invoice_id
                     ->first();
     
                 if (!$existingNotification) {
                     // Buat satu notifikasi untuk seluruh buku yang terlambat pada invoice ini
                     Notification::create([
                         'user_id'    => $invoice->user_id,
                         'admin_id'   => null, // Sesuaikan jika ada admin yang terkait
                         'book_id'    => null, // Tidak perlu mencatat book_id di sini
                         'type'       => 'due',
                         'message'    => 'Invoice #' . $invoice->id . ': Buku-buku berikut telah terlambat dikembalikan: ' . implode(', ', $overdueBooks),
                         'is_read'    => false,
                         'invoice_id' => $invoice->id, // Tambahkan ID invoice ke notifikasi
                     ]);
                 }
             }
         }
     
         return $next($request);
     }
     
}
