<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Menampilkan semua admin
    public function index()
    {
        $admins = Admin::with('user')->get();
        return view('admin.pages.dashboard', compact('admins'));
    }

}
