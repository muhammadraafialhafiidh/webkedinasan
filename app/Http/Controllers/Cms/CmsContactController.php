<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Contact;
use App\Services\BrevoMailService;
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

    public function reply(Request $request, $id, BrevoMailService $brevoMailService)
    {
        $message = Contact::findOrFail($id);

        $validated = $request->validate([
            'reply' => 'required|string',
        ], [
            'reply.required' => 'Balasan wajib diisi.',
            'reply.string' => 'Format balasan tidak valid.',
        ]);

        // Validasi jika editor hanya mengirimkan tag HTML kosong seperti <p>&nbsp;</p>
        $cleanReply = Contact::htmlToPlainText($validated['reply']);
        if ($cleanReply === '') {
            return back()->withErrors(['reply' => 'Balasan wajib diisi.'])->withInput();
        }

        // 1. Simpan balasan ke database secara eksplisit (hanya field reply & replied_at)
        $message->reply = $validated['reply'];
        $message->replied_at = now();
        $message->save();

        // 2. Catat log aktivitas
        ActivityLog::record('reply_message', 'Pesan Masuk', "Membalas pesan dari {$message->name} ({$message->email})");

        // 3. Kirim email notifikasi balasan melalui Brevo API (menggunakan teks bersih berparagraf)
        $mailResult = $brevoMailService->sendContactReply($message, $cleanReply);

        if ($mailResult['success']) {
            return back()->with('success', 'Tanggapan berhasil disimpan dan email berhasil dikirim ke pengirim.');
        }

        // Jika DB berhasil tetapi Brevo gagal mengirim email
        return back()->with('warning', 'Tanggapan berhasil disimpan di database, namun email gagal dikirim ke pengirim (' . e($message->email) . '). Silakan periksa log sistem atau koneksi Brevo API.');
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
