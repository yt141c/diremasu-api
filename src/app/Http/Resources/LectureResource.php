<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id,
            'name' => $this->name,
            'video_url' => $this->video_url,
            'order' => $this->order,
            'description' => $this->when($request->route()->named('lecture'), $this->description),
            'course' => $this->whenLoaded('section', function () {
                return $this->section->course ? [
                    'public_id' => $this->section->course->public_id,
                    'name' => $this->section->course->name,
                ] : null;
            }),
            'section' => $this->whenLoaded('section', function () {
                return [
                    'public_id' => $this->section->public_id,
                    'name' => $this->section->name,
                ];
            }),
        ];
    }
}
