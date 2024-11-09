<?php

namespace App\Console\Commands;

use App\Models\Book;
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
        // Get books with due dates that are 2 days away
        $books = Book::whereDate('due_date', Carbon::now()->addDays(2)->toDateString())->get();

        foreach ($books as $book) {
            // Get the user who borrowed this book
            $user = $book->borrowings()->latest()->first()->user; // Assuming a "borrowings" relationship

            // Create notification for each user
            Notification::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'message' => "Reminder: Your book '{$book->title}' is due on {$book->due_date}. Please return it by then.",
                'type' => 'due',
            ]);

            $this->info('Notification sent for book: ' . $book->title);
        }

        $this->info('Due date notifications sent successfully.');
    }

}
