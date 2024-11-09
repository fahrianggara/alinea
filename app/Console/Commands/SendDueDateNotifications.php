<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDueDateNotifications extends Command
{
    protected $signature = 'send:due-date-notifications';
    protected $description = 'Send due date notifications to users whose borrowed books are due in 2 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Mendapatkan peminjaman dengan tanggal kembali 2 hari dari sekarang
        $borrowings = Borrowing::whereDate('return_date', Carbon::now()->addDays(2)->toDateString())->get();

        foreach ($borrowings as $borrowing) {
            // Mengambil pengguna dan buku terkait peminjaman ini
            $user = $borrowing->user;
            $book = $borrowing->book;

            // Membuat notifikasi untuk setiap pengguna
            Notification::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'message' => "Reminder: Your book '{$book->title}' is due on {$borrowing->return_date}. Please return it by then.",
                'type' => 'due',
            ]);

            $this->info('Notification sent for book: ' . $book->title);
        }

        $this->info('Due date notifications sent successfully.');
    }

}
