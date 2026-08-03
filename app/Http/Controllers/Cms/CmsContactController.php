<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Contact;
use Illuminate\Http\Request;

class CmsContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('cms.contact.index', compact('messages'));
    }

    public function show($id)
    {
        $message = Contact::findOrFail($id);

        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
            ActivityLog::record('read_message', 'Pesan Masuk', "Membaca pesan masuk dari: {$message->name}");
        }

        return view('cms.contact.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $message = Contact::findOrFail($id);

        $request->validate([
            'reply' => 'required|string',
        ], [
            'reply.required' => 'Isi balasan wajib diisi.',
        ]);

        $message->reply = $request->reply;
        $message->replied_at = now();
        $message->save();

        ActivityLog::record('reply_message', 'Pesan Masuk', "Membalas pesan dari {$message->name} ({$message->email})");

        return back()->with('success', 'Balasan pesan berhasil disimpan dan dikirim.');
    }

    public function markRead($id)
    {
        $message = Contact::findOrFail($id);
        $message->is_read = true;
        $message->save();

        ActivityLog::record('mark_read', 'Pesan Masuk', "Tandai sudah dibaca pesan #{$id}");

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $name = $message->name;
        $message->delete();

        ActivityLog::record('delete', 'Pesan Masuk', "Menghapus pesan masuk dari: {$name}");

        return redirect()->route('cms.pesan.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
