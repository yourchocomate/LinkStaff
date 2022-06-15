<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $post = json_decode($this->post_content);
        return [
            'title' => $post->title,
            'body' => $post->body,
            'user_id' => $this->user_id,
            'page_id' => $this->page_id,
            'created_at' => $this->created_at
        ];
    }
}
