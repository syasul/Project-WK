<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Leaves;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = Leaves::paginate(25);
        $users = User::all();
        return view('screens.manageLeavePage', compact('leaves', 'users'));
    }
}
