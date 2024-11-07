<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResResource;
use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    public function index()
    {


        return response()->json(
            new ResResource(Cart::where('user_id', Auth::id())->get(), true, "Books retrieved successfully"),
            200
        );
    }

    public function store($bookId)
    {
        $book = Book::find($bookId);

        if ($book) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ]);

            return response()->json(
                new ResResource($cart, true, "Book added to cart successfully"),
                200
            );
        } else {
            // Jika buku tidak ditemukan, berikan response error
            return response()->json(
                new ResResource('', false, "Book not found"),
                404
            );
        }
    }

    public function mycart()
    {
        $cart = Cart::where('user_id', Auth::id())->get();
        return response()->json(
            new ResResource($cart, true, "Carts retrieved successfully"),
            200
        );
    }

    public function destroy($cartId)
    {
        $cart = Cart::findOrFail($cartId);

        if (!$cart) {
            return response()->json(
                new ResResource('', false, "Delete Failed"),
                200
            );
        }

        $cart->delete();
        return response()->json(
            new ResResource($cart, true, "Delete successfully"),
            200
        );
    }
}
