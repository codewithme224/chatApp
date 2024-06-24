<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        $users = $users->except(auth()->id());
        return Inertia::render('Dashboard', [
            'users' => $users,
            'auth' => [
                'user' => Auth::user(),
            ],
        ]);
    }


    public function chat(Request $request)
    {
        $friendId = $request->route('friend');
        $friend = User::findOrFail($friendId);
        return Inertia::render('Chat', [
            'friend' => $friend
        ]);
    }


    public function messages(Request $request, $friend)
    {
        $senderId = auth()->id();
        $receiverId = $friend;

        $messages = ChatMessage::where(function ($query) use ($senderId, $receiverId) {
                                $query->where('sender_id', $senderId)
                                    ->where('receiver_id', $receiverId);
                            })
                            ->orWhere(function ($query) use ($senderId, $receiverId) {
                                $query->where('sender_id', $receiverId)
                                    ->where('receiver_id', $senderId);
                            })
                            ->orderBy('id', 'asc')
                            ->get();

        return response()->json($messages);
    }



    public function sendMessage()
    {
        $data = request()->all();
        $data['sender_id'] = auth()->id();
        ChatMessage::create($data);
        return response()->json(['status' => 'success']);
    }

}
