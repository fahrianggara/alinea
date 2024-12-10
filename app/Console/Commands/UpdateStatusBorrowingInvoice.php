<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Models\Invoice;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateStatusBorrowingInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:borrowing-invoice-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status borrowing dan invoice, serta membuat notifikasi untuk buku yang terlambat dikembalikan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Command update:borrowing-invoice-status started.');

        // Update borrowing yang sudah lewat tanggal pengembalian
        Borrowing::where('return_date', '<', Carbon::now()->subDay())
            ->where('status_id', '!=', 4) // Status 'returned'
            ->update(['status_id' => 4]); // Update status menjadi 'overdue'

        Log::info('Borrowing overdue status updated.');

        // Ambil borrowing yang statusnya 'overdue' untuk membuat notifikasi
        $overdueBorrowings = Borrowing::with('book', 'user', 'invoice') // Tambahkan relasi ke invoice
            ->where('status_id', 4) // Status 'overdue'
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            // Cek apakah notifikasi sudah ada untuk kombinasi user_id, book_id, dan type
            $existingNotification = Notification::where([
                ['user_id', '=', $borrowing->user_id],
                ['type', '=', 'due'],
                ['book_id', '=', $borrowing->book_id],
            ])->first();

            if (!$existingNotification) {
                // Buat notifikasi baru
                Notification::create([
                    'user_id'  => $borrowing->user_id,
                    'admin_id' => null,
                    'book_id'  => $borrowing->book_id,
                    'type'     => 'due',
                    'message'  => 'Buku "' . $borrowing->book->title . '" sudah terlambat dikembalikan.',
                    'is_read'  => false,
                ]);

                Log::info('Notification created for user_id: ' . $borrowing->user_id . ' - book: ' . $borrowing->book->title);
            } else {
                Log::info('Notification already exists for user_id: ' . $borrowing->user_id . ' - book: ' . $borrowing->book->title);
            }

            // Perbarui status invoice jika ada borrowing overdue
            if ($borrowing->invoice) {
                $borrowing->invoice->update(['status' => 'fined']);
                Log::info('Invoice #' . $borrowing->invoice->id . ' updated to fined.');
            }
        }

        Log::info('Command update:borrowing-invoice-status completed.');

        return Command::SUCCESS;
    }
}
