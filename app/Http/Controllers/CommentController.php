<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petition;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Petition $petition)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $petition->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return back();
    }
    //
}
