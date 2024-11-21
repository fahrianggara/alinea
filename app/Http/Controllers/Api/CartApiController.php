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

    public function mycart()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('book.category')
            ->get();

        return response()->json(
            new ResResource($cart, true, "Carts retrieved successfully"),
            200
        );
    }

    public function store($bookId)
    {
        try {
            $book = Book::find($bookId);

            if (!$book) {
                return response()->json(
                    new ResResource('', false, "Book not found"),
                    404
                );
            }

            // Validasi apakah book_id sudah ada di cart untuk user saat ini
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->exists();
            if ($existingCart) {
                return response()->json(
                    new ResResource('', false, "Book is already in cart"),
                    400
                );
            }

            // Validasi apakah user sudah memiliki 3 buku di cart
            $cartCount = Cart::where('user_id', Auth::id())->count();
            if ($cartCount >= 3) {
                return response()->json(
                    new ResResource('', false, "Cart maximum limit reached (3 books)"),
                    400
                );
            }


            // Tambahkan buku ke cart
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ]);

            return response()->json(
                new ResResource($cart, true, "Book added to cart successfully"),
                200
            );
        } catch (\Exception $e) {
            // Menangani error tidak terduga
            return response()->json(
                new ResResource('', false, "An error occurred while adding book to cart"),
                500
            );
        }
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
