<?php

namespace Optimus\Media\Http\Controllers;

use Illuminate\Http\Request;
use Optix\Media\Models\Media;
use Optix\Media\MediaUploader;
use Illuminate\Routing\Controller;
use Optix\Media\Jobs\PerformConversions;
use Optimus\Media\Http\Resources\Media as MediaResource;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::filter($request)->get();

        return MediaResource::collection($media);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'file|max:' . config('media.max_file_size'),
            'folder_id' => 'exists:media_folders,id|nullable'
        ]);

        $media = MediaUploader::fromFile($request->file('file'))
            ->withAttributes([
                'folder_id' => $request->input('folder_id')
            ])
            ->upload();

        if (in_array($media->extension, ['bmp', 'gif', 'jpg', 'jpeg', 'png'])) {
            PerformConversions::dispatch($media, ['400x300']);
        }

        return new MediaResource($media);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);

        return new MediaResource($media);
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $request->validate([
            'name' => 'filled',
            'folder_id' => 'exists:media_folders,id|nullable'
        ]);

        $media->update([
            'name' => $request->input('name'),
            'folder_id' => $request->input('folder_id')
        ]);

        return new MediaResource($media);
    }

    public function destroy($id)
    {
        Media::findOrFail($id)->delete();

        return response(null, 204);
    }
}
