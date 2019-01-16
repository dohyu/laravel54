<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    public function store(Request $request)
    {
        $attachments = [];

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $file) {
                $filename = str_random() . filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
                $file->move(attachments_path(), $filename);
                $payload = [
                    'filename' => $filename,
                    'bytes' => $file->getClientSize(),
                    'mime' => $file->getClientMimeType()
                ];

                $attachments[] = ($id = $request->input('article_id'))
                    ? \App\Article::findOrFail($id)->attachments()->create($payload)
                    : \App\Attachment::create($payload);
            }
        }

        return response()->json($attachments, 200, [], JSON_PRETTY_PRINT);
    }

    public function show($file)
    {
        $path = attachments_path($file);

        if (! \File::exists($path)) {
            abort(404);
        }

        $image = \Image::make($path);

        return response($image->encode('png'), 200, [
            'Content-Type' => 'image/png'
        ]);
    }

    public function destroy(\App\Attachment $attachment)
    {
        $path = attachments_path($attachment->filename);

        if (\File::exists($path)) {
            \File::delete($path);
        }

        $attachment->delete();

        return response()->json($attachment, 200, [], JSON_PRETTY_PRINT);
    }
}
