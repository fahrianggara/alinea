<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
use App\Models\Notification;
use App\Models\User;
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
        $notification = Notification::where('user_id', Auth::id())->get;
        return response()->json(
            new ResResource($notification, true, "notification retrieved successfully"),
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
}
