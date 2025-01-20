<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {

        $users = User::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create() {
        return view('admin.user.create');
    }
}
