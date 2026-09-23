<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryVideo;
use App\Services\MediaSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CmsVideoController extends Controller
{
    public function index()
    {
        $videos = GalleryVideo::orderBy('created_at', 'desc')->paginate(10);
        return view('cms.gallery.video-index', compact('videos'));
    }

    public function store(Request $request)
    {
        $sourceType = $request->input('source_type', 'youtube');

        $rules = [
            'title' => 'required|string|max:200',
            'source_type' => ['required', Rule::in(['youtube', 'instagram', 'file', 'google_drive'])],
            'url' => [
                Rule::requiredIf(fn () => in_array($request->input('source_type'), ['youtube', 'instagram', 'google_drive'])),
                'nullable',
                'url',
                'max:500',
            ],
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];

        $messages = [
            'title.required' => 'Judul video wajib diisi.',
            'source_type.required' => 'Tipe sumber video wajib dipilih.',
            'source_type.in' => 'Tipe sumber video tidak valid.',
            'url.required' => 'URL video wajib diisi.',
            'url.url' => 'Format URL tidak valid.',
            'video_file.required' => 'File video wajib diunggah.',
            'video_file.mimes' => 'Format file video harus MP4, WebM, MOV, atau OGG.',
            'video_file.max' => 'Ukuran file video maksimal 100MB.',
        ];

        if ($sourceType === 'file') {
            $rules['video_file'] = 'required|file|mimes:mp4,webm,ogg,qt,mov|max:102400';
        }

        $request->validate($rules, $messages);

        if ($sourceType === 'google_drive') {
            if (!MediaSourceService::isValidGoogleDriveUrl($request->url)) {
                return back()->withErrors(['url' => 'Link Google Drive tidak valid.'])->withInput();
            }
        }

        if ($sourceType === 'instagram') {
            if (!MediaSourceService::isValidInstagramUrl($request->url)) {
                return back()->withErrors(['url' => 'URL postingan/reel Instagram tidak valid.'])->withInput();
            }
            if (GalleryVideo::where('url', $request->url)->exists()) {
                return back()->withErrors(['url' => 'URL video Instagram ini sudah pernah ditambahkan sebelumnya.'])->withInput();
            }
        }

        $videoFilePath = null;
        if ($sourceType === 'file' && $request->hasFile('video_file')) {
            $videoFilePath = $request->file('video_file')->store('videos', 'public');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('video_thumbnails', 'public');
        }

        $video = GalleryVideo::create([
            'title' => $request->title,
            'source_type' => $sourceType,
            'url' => $sourceType !== 'file' ? $request->url : null,
            'video_file' => $videoFilePath,
            'thumbnail' => $thumbnailPath,
            'description' => $request->description,
        ]);

        ActivityLog::record('create', 'Galeri Video', "Menambahkan video ({$sourceType}): {$video->title}");

        return back()->with('success', 'Video berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $video = GalleryVideo::findOrFail($id);

        $sourceType = $request->input('source_type', $video->source_type ?? 'youtube');

        $rules = [
            'title' => 'required|string|max:200',
            'source_type' => ['required', Rule::in(['youtube', 'instagram', 'file', 'google_drive'])],
            'url' => [
                Rule::requiredIf(fn () => in_array($sourceType, ['youtube', 'instagram', 'google_drive'])),
                'nullable',
                'url',
                'max:500',
            ],
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];

        $messages = [
            'title.required' => 'Judul video wajib diisi.',
            'source_type.required' => 'Tipe sumber video wajib dipilih.',
            'source_type.in' => 'Tipe sumber video tidak valid.',
            'url.required' => 'URL video wajib diisi.',
            'url.url' => 'Format URL tidak valid.',
            'video_file.required' => 'File video wajib diunggah.',
            'video_file.mimes' => 'Format file video harus MP4, WebM, MOV, atau OGG.',
            'video_file.max' => 'Ukuran file video maksimal 100MB.',
        ];

        if ($sourceType === 'file') {
            if (empty($video->video_file)) {
                $rules['video_file'] = 'required|file|mimes:mp4,webm,ogg,qt,mov|max:102400';
            } else {
                $rules['video_file'] = 'nullable|file|mimes:mp4,webm,ogg,qt,mov|max:102400';
            }
        }

        $request->validate($rules, $messages);

        if ($sourceType === 'google_drive') {
            if (!MediaSourceService::isValidGoogleDriveUrl($request->url)) {
                return back()->withErrors(['url' => 'Link Google Drive tidak valid.'])->withInput();
            }
        }

        if ($sourceType === 'instagram') {
            if (!MediaSourceService::isValidInstagramUrl($request->url)) {
                return back()->withErrors(['url' => 'URL postingan/reel Instagram tidak valid.'])->withInput();
            }
            if (GalleryVideo::where('url', $request->url)->where('id', '!=', $id)->exists()) {
                return back()->withErrors(['url' => 'URL video Instagram ini sudah digunakan pada video lain.'])->withInput();
            }
        }

        $video->title = $request->title;
        $video->source_type = $sourceType;
        $video->description = $request->description;

        $oldVideoFileToDelete = null;

        if ($sourceType !== 'file') {
            $video->url = $request->url;
            if ($video->video_file) {
                $oldVideoFileToDelete = $video->video_file;
                $video->video_file = null;
            }
        } else {
            $video->url = null;
            if ($request->hasFile('video_file')) {
                if ($video->video_file && Storage::disk('public')->exists($video->video_file)) {
                    Storage::disk('public')->delete($video->video_file);
                }
                $video->video_file = $request->file('video_file')->store('videos', 'public');
            }
        }

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $video->thumbnail = $request->file('thumbnail')->store('video_thumbnails', 'public');
        }

        $video->save();

        if ($oldVideoFileToDelete && Storage::disk('public')->exists($oldVideoFileToDelete)) {
            Storage::disk('public')->delete($oldVideoFileToDelete);
        }

        ActivityLog::record('update', 'Galeri Video', "Mengubah video ID #{$video->id}: {$video->title}");

        return back()->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $video = GalleryVideo::findOrFail($id);
        $title = $video->title;

        if ($video->video_file && Storage::disk('public')->exists($video->video_file)) {
            Storage::disk('public')->delete($video->video_file);
        }

        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        ActivityLog::record('delete', 'Galeri Video', "Menghapus video: {$title}");

        return back()->with('success', 'Video berhasil dihapus.');
    }
}
