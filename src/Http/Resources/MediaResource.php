<?php

namespace Optimus\Media\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MediaResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'folder_id' => $this->folder_id,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'extension' => $this->extension,
            'url' => $this->getUrl(),
            'thumbnail_url' => $this->getUrl('400x300'),
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'collection' => $this->whenPivotLoaded('mediables', function () {
                return $this->pivot->collection;
            }),
            'updated_at' => (string) $this->updated_at,
            'created_at' => (string) $this->created_at
        ];
    }
}