<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\DetailChat;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $chat = $user->chats()->latest()->first();
        $messages = $chat ? $chat->detailChats()->with('pengirim')->orderBy('id')->get() : collect();

        return view('chat', [
            'chat' => $chat,
            'messages' => $messages,
            'initialMessages' => $this->formatMessages($messages, $user->id),
        ]);
    }

    private function formatMessages($messages, int $userId)
    {
        return $messages->map(fn (DetailChat $m) => [
            'id' => $m->id,
            'mine' => $m->pengirim_id === $userId,
            'nama' => $m->pengirim->name,
            'pesan' => $m->pesan,
            'waktu' => $m->created_at->format('d M Y, H:i'),
        ])->values()->all();
    }

    public function poll(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['last_seen_at' => now()]);

        $chat = $user->chats()->latest()->first();
        $messages = $chat ? $chat->detailChats()->with('pengirim')->orderBy('id')->get() : collect();

        if ($chat) {
            $chat->detailChats()
                ->where('pengirim_id', '!=', $user->id)
                ->where('dibaca', false)
                ->update(['dibaca' => true]);
        }

        $admin = $chat?->admin;

        return response()->json([
            'chat' => $chat ? [
                'status' => $chat->status,
            ] : null,
            'messages' => $messages->map(fn (DetailChat $m) => [
                'id' => $m->id,
                'mine' => $m->pengirim_id === $user->id,
                'nama' => $m->pengirim->name,
                'pesan' => $m->pesan,
                'waktu' => $m->created_at->format('d M Y, H:i'),
            ]),
            'admin_online' => $admin !== null && $admin->isOnline(),
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pesan' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        $chat = $user->chats()->latest()->first();

        if (! $chat || $chat->status === 'selesai') {
            $admin = User::where('role', 'admin')->orderBy('id')->first();

            if (! $admin) {
                return response()->json(['message' => 'Belum ada admin yang tersedia.'], 422);
            }

            $chat = Chat::create([
                'pelanggan_id' => $user->id,
                'admin_id' => $admin->id,
                'status' => 'aktif',
            ]);
        }

        $pesan = $chat->detailChats()->create([
            'pengirim_id' => $user->id,
            'pesan' => $validated['pesan'],
        ]);

        $chat->update(['last_message_at' => now()]);

        return response()->json([
            'message' => [
                'id' => $pesan->id,
                'mine' => true,
                'nama' => $user->name,
                'pesan' => $pesan->pesan,
                'waktu' => $pesan->created_at->format('d M Y, H:i'),
            ],
        ]);
    }
}
