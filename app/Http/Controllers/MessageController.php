<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $users = User::query()->where('id', '!=', auth()->id())->get();
        return view('message', compact('users'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        Message::query()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('messages-show', $request['receiver_id']);
    }

    public function show(string $id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $users = User::query()->where('id', '!=', auth()->id())->get();
        $next_user = User::query()->findOrFail($id);

        $messages = Message::query()->where(function ($query) use ($id){
           $query->where('sender_id', auth()->id())->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id){
            $query->where('sender_id', $id)->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return view('message', ['users' => $users, 'next_user' => $next_user, 'messages' => $messages]);
    }

    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
