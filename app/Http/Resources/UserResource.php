<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'email' => $this->email,
            'photo' => $this->avatar,
            'middlename' => $this->middlename,
            'suffixname' => $this->suffixname,
            'prefixname' => $this->prefixname,
            'fullname' => $this->full_name,
            'type' => $this->type,
            'deleted_at' => $this->deleted_at
        ];
    }
}
