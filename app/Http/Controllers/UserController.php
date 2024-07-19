<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $employees = User::all();
        return view('pages.master-employees', compact('employees'));
    }
}
