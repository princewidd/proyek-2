<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\DetailChat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatAdminController extends Controller
{
    public function index(Request $request): View
    {
        $chats = Chat::with(['pelanggan', 'admin', 'detailChats' => fn ($q) => $q->latest('id')])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get();

        $unread = DetailChat::selectRaw('chat_id, COUNT(*) as total')
            ->where('dibaca', false)
            ->where('pengirim_id', '!=', $request->user()->id)
            ->whereIn('chat_id', $chats->pluck('id'))
            ->groupBy('chat_id')
            ->pluck('total', 'chat_id');

        return view('admin.chat', [
            'chats' => $chats,
            'unread' => $unread,
        ]);
    }

    public function show(Request $request, Chat $chat): View
    {
        abort_if($chat->admin_id !== $request->user()->id && $chat->admin_id !== null, 403);

        if ($chat->admin_id === null) {
            $chat->update(['admin_id' => $request->user()->id]);
        }

        $messages = $chat->detailChats()->with('pengirim')->orderBy('id')->get();

        return view('admin.chat-show', [
            'chat' => $chat->load('pelanggan'),
            'messages' => $messages,
            'initialMessages' => $messages->map(fn (DetailChat $m) => [
                'id' => $m->id,
                'mine' => $m->pengirim_id === $request->user()->id,
                'nama' => $m->pengirim->name,
                'pesan' => $m->pesan,
                'waktu' => $m->created_at->format('d M Y, H:i'),
            ])->values()->all(),
        ]);
    }

    public function poll(Request $request, Chat $chat): JsonResponse
    {
        abort_if($chat->admin_id !== $request->user()->id && $chat->admin_id !== null, 403);

        $user = $request->user();
        $user->update(['last_seen_at' => now()]);

        $chat->detailChats()
            ->where('pengirim_id', '!=', $user->id)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        $messages = $chat->detailChats()->with('pengirim')->orderBy('id')->get();

        return response()->json([
            'status' => $chat->status,
            'pelanggan_online' => $chat->pelanggan->isOnline(),
            'messages' => $messages->map(fn (DetailChat $m) => [
                'id' => $m->id,
                'mine' => $m->pengirim_id === $user->id,
                'nama' => $m->pengirim->name,
                'pesan' => $m->pesan,
                'waktu' => $m->created_at->format('d M Y, H:i'),
            ]),
        ]);
    }

    public function send(Request $request, Chat $chat): JsonResponse
    {
        abort_if($chat->admin_id !== $request->user()->id && $chat->admin_id !== null, 403);

        $validated = $request->validate([
            'pesan' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        if ($chat->admin_id === null) {
            $chat->update(['admin_id' => $user->id]);
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
