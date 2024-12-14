<?php

namespace App\Listeners;

use App\Events\NewBookAdded;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewBookNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewBookAdded $event)
    {
        $book = $event->book;
        $users = User::where('role', 'user')->get(); // Fetch all users to notify

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => "A new book titled '{$book->title}' has been added.",
                'book_id' => $book->id,
                'type' => 'recommendations',
            ]);
        }
    }
}
