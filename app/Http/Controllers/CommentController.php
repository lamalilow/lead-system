<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use App\Models\Lead;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($id)
    {
        $comments = Lead::findOrFail($id)->comments()->with('user')->latest()->get();
        return response()->json($comments);
    }

    public function store($id, StoreRequest $request)
    {
        $request->validated();
        $comment = Lead::findOrFail($id)->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->body
        ]);
        return response()->json($comment, 201);
    }
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->noContent();
    }
}
