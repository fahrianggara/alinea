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
}
