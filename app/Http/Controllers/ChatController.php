<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatBot;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'guest@example.com'],
            ['name' => 'Guest', 'password' => bcrypt('password')]
        );

        $conversationId = session('conversation_id');

        if ($conversationId) {
            $response = (new ChatBot)->continue($conversationId, as: $user)
                ->prompt($request->message);
        } else {
            $response = (new ChatBot)->forUser($user)
                ->prompt($request->message);
            session(['conversation_id' => $response->conversationId]);
        }

        return response()->json(['reply' => $response->text]);
    }
}