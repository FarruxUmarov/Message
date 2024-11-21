<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(): View|Factory|Application
    {
        $users = User::query()->where('id', '!=', auth()->id())->get();
        return view('chat', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'chats' => 'required|string|max:1000'
        ]);
        Chat::query()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'chat' => $validated['chats'],
        ]);

        return redirect()->route('chats-show', $request['receiver_id']);
    }

    public function show(string $id): View|Factory|Application
    {
        $users = User::query()->where('id', '!=', auth()->id())->get();

        $user_account = auth()->user();

        $next_user = User::query()->findOrFail($id);

        $chats = Chat::query()->where(function ($query) use ($id){
            $query->where('sender_id', auth()->id())
            ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id){
            $query->where('sender_id', $id)
            ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')
        ->get();

        $user_chat = Chat::query()->where('sender_id', auth()->id())
        ->orderBy('created_at', 'asc')
        ->first();

        return view('chat', [
            'users' => $users,
            'next_user' => $next_user,
            'chats' => $chats,
            'user_chat' => $user_chat,
            'user_account' => $user_account
        ]);
    }
}
