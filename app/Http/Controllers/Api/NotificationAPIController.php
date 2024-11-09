<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationAPIController extends Controller
{
    // notifikasi buku baru
    public function index()
    {
        $notifications = Notification::all();
        return response()->json(
            new ResResource($notifications, true, "notification retrieved successfully"),
            200
        );
    }
    // notif buku baru untuk user yang sudah login
    public function mynotif()
    {
        $notifications = Notification::where('user_id', Auth::id())->get;
        return response()->json(
            new ResResource($notifications, true, "notification retrieved successfully"),
            200
        );
    }
    public function newBookNotification($bookId)
    {
        $book = Book::find($bookId);

        if ($book) {
            $users = User::where('role', 'user')->get(); // Fetch all users to notify

            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'message' => "A new book titled '{$book->title}' has been added.",
                    'type' => 'recommendations',
                ]);
            }

            return response()->json(['message' => 'Notifications sent for new book.'], 200);
        }

        return response()->json(['error' => 'Book not found.'], 404);
    }

    public function dueDateNotification($userId, $bookId)
    {
        $user = User::find($userId);
        $book = Book::find($bookId);

        // Fetch the borrowing record for the given user and book
        $borrowing = Borrowing::where('user_id', $userId)->where('book_id', $bookId)->first();

        if ($user && $book && $borrowing) {
            // Get the return_date (due date) from the borrowing record
            $dueDate = $borrowing->return_date;

            // Calculate the notification date: two days before the due date
            $notificationDate = Carbon::parse($dueDate)->subDays(2)->format('Y-m-d');

            // Compare the notification date with today's date
            if (Carbon::now()->format('Y-m-d') === $notificationDate) {
                // Create the notification
                Notification::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'message' => "Reminder: Your book '{$book->title}' is due on {$dueDate}. Please return it by then.",
                    'type' => 'due',
                ]);

                return response()->json(['message' => 'Due date notification sent.'], 200);
            }

            return response()->json(['message' => 'No notification needed today.'], 200);
        }

        return response()->json(['error' => 'User, Book, or Borrowing record not found.'], 404);
    }
}
