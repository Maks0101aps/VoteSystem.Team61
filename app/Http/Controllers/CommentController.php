<?php

namespace App\Http\Controllers;

use App\Events\NewCommentAdded;
use Illuminate\Http\Request;
use App\Models\Petition;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $commentable_id)
    {
        $commentable_type = $request->input('commentable_type');

        $request->validate([
            'content' => 'required|string',
            'commentable_type' => 'required|string|in:App\Models\Petition,App\Models\Voting',
        ]);

        $commentable = $commentable_type::findOrFail($commentable_id);

        $comment = $commentable->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        // Загружаем пользователя для корректного отображения имени
        $comment->load('user');

        // Отправляем событие о новом комментарии
        try {
            event(new NewCommentAdded($comment));
        } catch (\Exception $e) {
            // Логируем ошибку, но не мешаем основному процессу
            \Log::error('Ошибка отправки события комментария: ' . $e->getMessage());
        }

        return back();
    }
    //
}
